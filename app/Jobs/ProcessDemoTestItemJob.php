<?php

namespace App\Jobs;

use App\DataTransferObjects\DemoTestDto;
use App\Enums\DemoTestStatus;
use App\Models\DemoTest;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ProcessDemoTestItemJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private DemoTestDto $demoTestDto,
        private bool $shouldFail = false
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->shouldFail) {
                throw new \Exception("Simulated failure.");
            }

            $demoTest = DemoTest::where('ref', $this->demoTestDto->ref)->first();
            if (!$demoTest) {
                $demoTest = new DemoTest();
                $demoTest->ref = $this->demoTestDto->ref;
                $demoTest->name = $this->demoTestDto->name;
                $demoTest->description = $this->demoTestDto->description;
                $demoTest->status = DemoTestStatus::New->value;
            } else {
                $demoTest->status = DemoTestStatus::Updated->value;
            }
            $demoTest->save();
        } catch (\Exception $exception) {
            if ($this->attempts() < $this->tries) {
                $this->release(1);
            } else {
                throw $exception;
            }
        }
    }

    /**
     * Determine whether the job should fail or not.
     * 
     * @param int $jobKey The index of the current job within the batch.
     * @param int $batchCount The total number of jobs in the batch.
     */
    public function setupShouldFail($jobKey, $batchCount)
    {
        $failCount = (int) floor($batchCount * 0.1);

        $failingIndices = array_slice(range(0, $batchCount - 1), 0, $failCount);

        $this->shouldFail = in_array($jobKey, $failingIndices);
    }
}
