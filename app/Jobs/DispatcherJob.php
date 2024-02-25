<?php

namespace App\Jobs;

use App\DataTransferObjects\DemoTestDto;
use App\Enums\InquiryStatus;
use App\Models\DemoTestInquiry;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

class DispatcherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private DemoTestInquiry $demoTestInquiry;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $demoTestInquiryId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $demoTestInquiry = DemoTestInquiry::findOrFail($this->demoTestInquiryId);

        $demoTestCollectionDto = DemoTestDto::fromJsonArray($demoTestInquiry->payload);

        $demoTestCollectionCount = count($demoTestCollectionDto);

        $jobs = $demoTestCollectionDto->map(function ($demoTestDto, $demoTestDtoKey) use ($demoTestCollectionCount) {
            $job = new ProcessDemoTestItemJob($demoTestDto);

            if (config('job.enable_failing_jobs', false)) {
                $job->setupShouldFail($demoTestDtoKey, $demoTestCollectionCount);
            }

            return $job;
        });

        Bus::batch($jobs)
            ->allowFailures()
            ->then(function () use ($demoTestInquiry) {
                $demoTestInquiry->update(['status' => InquiryStatus::Processed->value]);
            })
            ->catch(function () use ($demoTestInquiry) {
                $demoTestInquiry->update(['status' => InquiryStatus::Failed->value]);
            })
            ->finally(function (Batch $batch) use ($demoTestInquiry) {
                $demoTestInquiry->update([
                    'items_processed_count' => $batch->processedJobs(),
                    'items_failed_count' => $batch->failedJobs,
                ]);
            })
            ->name('process-demo-test-inquiry-' . $demoTestInquiry->id)
            ->dispatch();
    }
}
