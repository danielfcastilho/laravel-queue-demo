<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessDemoTestRequest;
use App\Jobs\DispatcherJob;
use App\Models\DemoTest;
use App\Repositories\DemoTestInquiryRepositoryInterface;
use App\Repositories\DemoTestRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DemoTestController extends Controller
{
    public function __construct(
        protected DemoTestInquiryRepositoryInterface $demoTestInquiryRepository,
        protected DemoTestRepositoryInterface $demoTestRepository
    ) {
    }

    /**
     * Processes a new demo test inquiry by validating the request data,
     * storing the inquiry details, and dispatching a job for further processing.
     *
     * @param  ProcessDemoTestRequest  $request
     * @return JsonResponse
     */
    public function process(ProcessDemoTestRequest $request): JsonResponse
    {
        $payload = $request->validated();

        try {
            $demoTestInquiry = $this->demoTestInquiryRepository->create($payload);

            DispatcherJob::dispatch($demoTestInquiry->id);
        } catch (\Throwable $e) {
            Log::error("Error processing demo test: {$e->getMessage()}");

            return response()->json([
                'message' => 'Something went wrong when trying to process the demo test.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([], Response::HTTP_CREATED);
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

        try {
            $this->demoTestRepository->activate($demoTest);
        } catch (\Throwable $e) {
            Log::error("Error activating demo test: {$e->getMessage()}");

            return response()->json([
                'message' => 'Something went wrong when trying to activate the demo test.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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

        try {
            $this->demoTestRepository->deactivate($demoTest);
        } catch (\Throwable $e) {
            Log::error("Error deactivating demo test: {$e->getMessage()}");

            return response()->json([
                'message' => 'Something went wrong when trying to deactivate the demo test.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Demo test deactivated successfully.'
        ], Response::HTTP_OK);
    }
}
