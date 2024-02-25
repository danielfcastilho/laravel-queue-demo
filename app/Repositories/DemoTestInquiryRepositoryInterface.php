<?php

namespace App\Repositories;

use App\Models\DemoTestInquiry;

interface DemoTestInquiryRepositoryInterface
{
    /**
     * Create a new DemoTestInquiry with the given payload.
     *
     * @param array $payload
     * @return DemoTestInquiry
     * @throws \Exception
     */
    public function create(array $payload): DemoTestInquiry;

    /**
     * Find a DemoTestInquiry by its ID.
     *
     * @param int $id
     * @return DemoTestInquiry|null
     */
    public function find(int $id): ?DemoTestInquiry;

    /**
     * Update the status of a DemoTestInquiry.
     *
     * @param DemoTestInquiry $demoTestInquiry
     * @param string $status
     * @return void
     * @throws \Exception
     */
    public function updateStatus(DemoTestInquiry $demoTestInquiry, string $status): void;

    /**
     * Update the counts of processed and failed jobs for a DemoTestInquiry.
     *
     * @param DemoTestInquiry $demoTestInquiry
     * @param int $processedCount
     * @param int $failedCount
     * @return void
     * @throws \Exception
     */
    public function updateCounts(DemoTestInquiry $demoTestInquiry, int $processedCount, int $failedCount): void;
}
