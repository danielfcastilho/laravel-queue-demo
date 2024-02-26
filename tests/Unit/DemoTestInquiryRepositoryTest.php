<?php

namespace Tests\Feature;

use App\Enums\InquiryStatus;
use App\Models\DemoTestInquiry;
use App\Repositories\DemoTestInquiryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoTestInquiryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DemoTestInquiryRepository();
    }

    public function test_it_creates_demo_test_inquiry_successfully()
    {
        $payload = [['key' => 'value'], ['key' => 'another value']];

        $inquiry = $this->repository->create($payload);

        $this->assertDatabaseHas('demo_test_inquiry', [
            'id' => $inquiry->id,
            'payload' => json_encode($payload)
        ]);
    }

    public function test_it_finds_a_demo_test_inquiry_by_id()
    {
        $inquiry = DemoTestInquiry::factory()->create();

        $foundInquiry = $this->repository->find($inquiry->id);

        $this->assertNotNull($foundInquiry);
        $this->assertEquals($inquiry->id, $foundInquiry->id);
    }

    public function test_it_returns_false_when_inquiry_not_found()
    {
        $foundInquiry = $this->repository->find(999);

        $this->assertNull($foundInquiry);
    }

    public function test_it_updates_the_status_of_a_demo_test_inquiry()
    {
        $inquiry = DemoTestInquiry::factory()->create([
            'status' => InquiryStatus::Active->value
        ]);

        $this->repository->updateStatus($inquiry, InquiryStatus::Processed->value);

        $this->assertDatabaseHas('demo_test_inquiry', [
            'id' => $inquiry->id,
            'status' => InquiryStatus::Processed->value
        ]);
    }

    public function test_it_updates_the_counts_of_a_demo_test_inquiry()
    {
        $inquiry = DemoTestInquiry::factory()->create();

        $this->repository->updateCounts($inquiry, 10, 2);

        $this->assertDatabaseHas('demo_test_inquiry', [
            'id' => $inquiry->id,
            'items_processed_count' => 10,
            'items_failed_count' => 2
        ]);
    }

    public function test_it_throws_exception_when_updating_counts_of_a_nonexistent_inquiry()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentInquiry = new DemoTestInquiry();
        $nonExistentInquiry->id = 999;

        $this->repository->updateCounts($nonExistentInquiry, 2, 0);
    }

    public function test_it_throws_exception_when_updating_nonexistent_inquiry()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentInquiry = new DemoTestInquiry();
        $nonExistentInquiry->id = 999;

        $this->repository->updateStatus($nonExistentInquiry, InquiryStatus::Processed->value);
    }
}
