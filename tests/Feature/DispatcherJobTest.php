<?php

namespace Tests\Feature;

use App\Jobs\DispatcherJob;
use App\Jobs\ProcessDemoTestItemJob;
use App\Models\DemoTestInquiry;
use App\Repositories\DemoTestInquiryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Testing\Fakes\PendingBatchFake;
use Tests\TestCase;

class DispatcherJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatcher_job_processes_inquiry_successfully()
    {
        Queue::fake();

        $demoTestInquiry = DemoTestInquiry::factory()->create();

        DispatcherJob::dispatch($demoTestInquiry->id);

        Queue::assertPushed(DispatcherJob::class);
    }

    public function test_dispatcher_job_pushes_individual_jobs_successfully()
    {
        Queue::fake();

        $demoTestInquiry = DemoTestInquiry::factory()->create();

        $respository = $this->app->make(DemoTestInquiryRepositoryInterface::class);

        $job = new DispatcherJob($demoTestInquiry->id);
        $job->dispatch($demoTestInquiry->id);
        $job->handle($respository);

        Queue::assertPushed(ProcessDemoTestItemJob::class);
    }

    public function test_dispatcher_successfully_batches_individual_jobs()
    {
        Bus::fake([ProcessDemoTestItemJob::class]);

        $demoTestInquiry = DemoTestInquiry::factory()->create();

        $respository = $this->app->make(DemoTestInquiryRepositoryInterface::class);

        $job = new DispatcherJob($demoTestInquiry->id);
        $job->dispatch($demoTestInquiry->id);
        $job->handle($respository);

        Bus::assertBatched(function (PendingBatchFake $batch) use ($demoTestInquiry) {
            return $batch->jobs->count() === 2;
        });
    }
}
