<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemoTestProcessRequest;
use App\Jobs\BatchDispatcherJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DemoTestController extends Controller
{
    public function __construct()
    {
    }

    public function process(DemoTestProcessRequest $request)
    {
        BatchDispatcherJob::dispatch($request->validated());

        return response(null, Response::HTTP_CREATED);
    }

    public function activate($ref)
    {
    }

    public function deactivate($ref)
    {
    }
}
