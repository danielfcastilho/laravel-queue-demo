<?php

namespace Tests\Feature;

use App\Jobs\DispatcherJob;
use App\Models\DemoTest;
use App\Repositories\DemoTestInquiryRepositoryInterface;
use App\Repositories\DemoTestRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class DemoTestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_processes_demo_test_inquiry_successfully()
    {
        Queue::fake();

        $payload = [
            [
                "ref" => "T-1",
                "name" => "test",
                "description" => null
            ],
            [
                "ref" => "T-2",
                "name" => "test",
                "description" => "Test description"
            ]
        ];

        $response = $this->json('POST', '/api/demo/test', $payload);

        $response->assertStatus(201);
        Queue::assertPushed(DispatcherJob::class);
    }

    public function test_it_fails_processing_demo_test_inquiry_due_to_validation_error()
    {
        $invalidPayload = [
            [
                "ref" => "T-1", //missing name
                "description" => null
            ],
            [
                "ref" => "T-2",
                "name" => "test",
                "description" => "Test description"
            ]
        ];

        $response = $this->json('POST', '/api/demo/test', $invalidPayload);

        $response->assertStatus(422);
    }

    public function test_it_handles_exceptions_during_demo_test_inquiry_processing()
    {
        $this->mock(DemoTestInquiryRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andThrow(new \Exception('Simulated exception'));
        });

        Log::shouldReceive('error')->once();

        $payload = [
            [
                "ref" => "T-1",
                "name" => "test",
                "description" => null
            ],
            [
                "ref" => "T-2",
                "name" => "test",
                "description" => "Test description"
            ]
        ];

        $response = $this->json('POST', '/api/demo/test', $payload);

        $response->assertStatus(500)->assertJson([
            'message' => 'Something went wrong when trying to process the demo test.'
        ]);
    }

    public function test_it_throws_error_when_trying_to_process_inactive_record()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => false]);

        $payload = [
            [
                "ref" => $demoTest->ref,
                "name" => $demoTest->name,
                "description" => $demoTest->description
            ],
        ];

        $response = $this->json('POST', '/api/demo/test', $payload);

        $response->assertStatus(422);
    }

    public function test_it_activates_a_demo_test_successfully()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => false]);

        $response = $this->json('POST', "/api/demo/test/activate/{$demoTest->ref}");

        $response->assertOk()->assertJson(['message' => 'Demo test activated successfully.']);
    }

    public function test_it_fails_to_activate_an_already_active_demo_test()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => true]);

        $response = $this->json('POST', "/api/demo/test/activate/$demoTest->ref");

        $response->assertStatus(422)->assertJson(['message' => 'The selected demo test must be active to be deactivated.']);
    }

    public function test_it_handles_exceptions_when_activating_a_demo_test()
    {
        Log::shouldReceive('error')->once();

        $demoTest = DemoTest::factory()->create(['is_active' => false]);

        $this->instance(DemoTestRepositoryInterface::class, Mockery::mock(DemoTestRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('activate')
                ->once()
                ->with(Mockery::type(DemoTest::class))
                ->andThrow(new \Exception('Simulated exception'));
        }));

        $response = $this->json('POST', "/api/demo/test/activate/$demoTest->ref");

        $response->assertInternalServerError()->assertJson(['message' => 'Something went wrong when trying to activate the demo test.']);
    }

    public function test_it_deactivates_a_demo_test_successfully()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => true]);

        $response = $this->json('POST', "/api/demo/test/deactivate/{$demoTest->ref}");

        $response->assertOk()->assertJson(['message' => 'Demo test deactivated successfully.']);
    }

    public function test_it_fails_to_deactivate_an_already_deactived_demo_test()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => false]);

        $response = $this->json('POST', "/api/demo/test/deactivate/$demoTest->ref");

        $response->assertStatus(422)->assertJson(['message' => 'The selected demo test must be deactivated to be activated.']);
    }

    public function test_it_handles_exceptions_when_deactivating_a_demo_test()
    {
        Log::shouldReceive('error')->once();

        $demoTest = DemoTest::factory()->create(['is_active' => true]);

        $this->instance(DemoTestRepositoryInterface::class, Mockery::mock(DemoTestRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('deactivate')
                ->once()
                ->with(Mockery::type(DemoTest::class))
                ->andThrow(new \Exception('Simulated exception'));
        }));

        $response = $this->json('POST', "/api/demo/test/deactivate/$demoTest->ref");

        $response->assertInternalServerError()->assertJson(['message' => 'Something went wrong when trying to deactivate the demo test.']);
    }
}
