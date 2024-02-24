<?php

namespace App\Listeners;

use App\Events\ProcessIndividualJobFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInquiryStatusListener
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
    public function handle(ProcessIndividualJobFinished $event): void
    {
        //
    }
}
