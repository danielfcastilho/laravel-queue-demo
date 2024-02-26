<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Failing Jobs
    |--------------------------------------------------------------------------
    |
    | The enable_failing_jobs config toggles the intentional failure of 10% of
    | ProcessDemoTestItemJob jobs to test the system's error handling. When enabled, it ensures
    | a controlled simulation of failures in job processing.
    |
    */

    'enable_failing_jobs' => env('ENABLE_FAILING_JOBS', false),
];
