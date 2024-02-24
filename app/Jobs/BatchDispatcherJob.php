<?php

namespace App\Jobs;

use App\Events\BatchDispatcherJobFinished;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BatchDispatcherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('dispatcherQueue');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        BatchDispatcherJobFinished::dispatch();
    }
}
