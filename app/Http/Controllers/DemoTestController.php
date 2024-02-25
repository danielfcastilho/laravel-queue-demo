<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessDemoTestRequest;
use App\Jobs\DispatcherJob;
use App\Models\DemoTest;
use App\Models\DemoTestInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DemoTestController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Processes a new demo test inquiry by validating the request data,
     * storing the inquiry details, and dispatching a job for further processing.
     *
     * @param  ProcessDemoTestRequest  $request
     * @return Response
     */
    public function process(ProcessDemoTestRequest $request): Response
    {
        $payload = $request->validated();

        $demoTestInquiry = new DemoTestInquiry();
        $demoTestInquiry->payload = json_encode($payload);
        $demoTestInquiry->items_total_count = count($payload);
        $demoTestInquiry->save();

        DispatcherJob::dispatch($demoTestInquiry->id);

        return response(null, Response::HTTP_CREATED);
    }

    /**
     * Activates the specified demo test.
     *
     * @param  DemoTest  $demoTest
     * @return JsonResponse
     */
    public function activate(DemoTest $demoTest): JsonResponse
    {
        if ($demoTest->is_active === true) {
            return response()->json([
                'message' => 'The selected demo test must be active to be deactivated.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $demoTest->is_active = true;
        $demoTest->save();

        return response()->json([
            'message' => 'Demo test activated successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * Deactivates the specified demo test.
     *
     * @param  DemoTest  $demoTest
     * @return JsonResponse
     */
    public function deactivate(DemoTest $demoTest): JsonResponse
    {
        if ($demoTest->is_active === false) {
            return response()->json([
                'message' => 'The selected demo test must be deactivated to be activated.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $demoTest->is_active = false;
        $demoTest->save();

        return response()->json([
            'message' => "Demo test deactivated successfully."
        ], Response::HTTP_OK);
    }
}
