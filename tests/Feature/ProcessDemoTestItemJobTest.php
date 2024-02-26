<?php

namespace Tests\Feature;

use App\DataTransferObjects\DemoTestDto;
use App\Jobs\ProcessDemoTestItemJob;
use App\Models\DemoTest;
use App\Repositories\DemoTestRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessDemoTestItemJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_demo_test_item_job_executes_successfully()
    {
        Queue::fake();

        $demoTest = DemoTest::factory()->create();

        $demoTestDto = new DemoTestDto(
            ref: $demoTest->ref,
            name: $demoTest->name,
            description: $demoTest->description
        );

        /** @var DemoTestRepositoryInterface */
        $mockRepository = $this->mock(DemoTestRepositoryInterface::class, function ($mock) use ($demoTest) {
            $mock->shouldReceive('createOrUpdate')->once()->andReturn($demoTest);
        });

        $job = new ProcessDemoTestItemJob($demoTestDto);
        $job->dispatch($demoTestDto);

        Queue::assertPushed(ProcessDemoTestItemJob::class);

        $job->handle($mockRepository);
    }
}
