<?php

namespace App\Jobs;

use App\DataTransferObjects\DemoTestDto;
use App\Enums\AvailableQueues;
use App\Enums\DemoTestStatus;
use App\Models\DemoTest;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDemoTestItemJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        // $this->onQueue(AvailableQueues::Processing->value);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->shouldFail) {
            $this->fail();
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

        $failingIndices = range(0, $batchCount - 1, (int) ceil(10 / ($failCount / $batchCount)));

        $this->shouldFail = in_array($jobKey, $failingIndices);
    }
}
