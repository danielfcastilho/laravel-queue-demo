<?php

namespace App\Repositories;

use App\Models\DemoTestInquiry;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DemoTestInquiryRepository implements DemoTestInquiryRepositoryInterface
{
    /**
     * Create a new DemoTestInquiry with the given payload.
     *
     * @param array $payload
     * @return DemoTestInquiry
     * @throws \Exception
     */
    public function create(array $payload): DemoTestInquiry
    {
        try {
            $demoTestInquiry = new DemoTestInquiry();
            $demoTestInquiry->payload = json_encode($payload);
            $demoTestInquiry->items_total_count = count($payload);
            $demoTestInquiry->save();

            return $demoTestInquiry;
        } catch (\Exception $e) {
            throw new \Exception("Failed to create DemoTestInquiry: " . $e->getMessage());
        }
    }

    /**
     * Find a DemoTestInquiry by its ID.
     *
     * @param int $id
     * @return DemoTestInquiry|null
     */
    public function find(int $id): ?DemoTestInquiry
    {
        return DemoTestInquiry::find($id);
    }

    /**
     * Update the status of a DemoTestInquiry.
     *
     * @param DemoTestInquiry $demoTestInquiry
     * @param string $status
     * @return void
     * @throws \Exception
     */
    public function updateStatus(DemoTestInquiry $demoTestInquiry, string $status): void
    {
        if (!$demoTestInquiry->exists) {
            throw new ModelNotFoundException("DemoTestInquiry does not exist and cannot be updated.");
        }

        try {
            $demoTestInquiry->status = $status;
            $demoTestInquiry->save();
        } catch (\Exception $e) {
            throw new \Exception("Failed to update DemoTestInquiry status: " . $e->getMessage());
        }
    }

    /**
     * Update the counts of processed and failed jobs for a DemoTestInquiry.
     *
     * @param DemoTestInquiry $demoTestInquiry
     * @param int $processedCount
     * @param int $failedCount
     * @return void
     * @throws \Exception
     */
    public function updateCounts(DemoTestInquiry $demoTestInquiry, int $processedCount, int $failedCount): void
    {
        if (!$demoTestInquiry->exists) {
            throw new ModelNotFoundException("DemoTestInquiry does not exist and cannot be updated.");
        }

        try {
            $demoTestInquiry->items_processed_count = $processedCount;
            $demoTestInquiry->items_failed_count = $failedCount;
            $demoTestInquiry->save();
        } catch (\Exception $e) {
            throw new \Exception("Failed to update DemoTestInquiry counts: " . $e->getMessage());
        }
    }
}
