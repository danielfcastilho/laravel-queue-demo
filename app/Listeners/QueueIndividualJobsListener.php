<?php

namespace App\Listeners;

use App\Events\BatchDispatcherJobFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class QueueIndividualJobsListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BatchDispatcherJobFinished $event): void
    {
        //
    }
}
