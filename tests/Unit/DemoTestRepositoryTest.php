<?php

namespace Tests\Unit;

use App\DataTransferObjects\DemoTestDto;
use App\Enums\DemoTestStatus;
use App\Models\DemoTest;
use App\Repositories\DemoTestRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoTestRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DemoTestRepository();
    }

    public function test_it_creates_a_new_demo_test_if_not_exists()
    {
        $dto = new DemoTestDto('T1', 'Test 1', 'Description 1');
        $repository = new DemoTestRepository();

        $repository->createOrUpdate($dto);

        $this->assertDatabaseHas('demo_test', [
            'ref' => 'T1',
            'name' => 'Test 1',
            'description' => 'Description 1',
            'status' => DemoTestStatus::New->value
        ]);
    }

    public function test_it_updates_an_existing_demo_test()
    {
        DemoTest::factory()->create([
            'ref' => 'T1',
            'status' => DemoTestStatus::New->value
        ]);

        $dto = new DemoTestDto('T1', 'Updated Name', 'Updated Description');
        $repository = new DemoTestRepository();

        $demoTest = $repository->createOrUpdate($dto);

        $this->assertEquals('Updated Name', $demoTest->name);
        $this->assertEquals('Updated Description', $demoTest->description);
        $this->assertEquals(DemoTestStatus::Updated, $demoTest->status);
    }

    public function test_it_activates_a_demo_test()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => false]);
        $repository = new DemoTestRepository();

        $success = $repository->activate($demoTest);

        $this->assertTrue($success);
        $this->assertTrue($demoTest->fresh()->is_active);
    }

    public function test_it_deactivates_a_demo_test()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => true]);
        $repository = new DemoTestRepository();

        $success = $repository->deactivate($demoTest);

        $this->assertTrue($success);
        $this->assertFalse($demoTest->fresh()->is_active);
    }

    public function test_it_throws_exception_when_activating_nonexistent_demo_test()
    {
        $this->expectException(ModelNotFoundException::class);
        $repository = new DemoTestRepository();
        $nonexistentDemoTest = new DemoTest();

        $repository->activate($nonexistentDemoTest);
    }

    public function test_it_throws_exception_when_deactivating_nonexistent_demo_test()
    {
        $this->expectException(ModelNotFoundException::class);
        $repository = new DemoTestRepository();
        $nonexistentDemoTest = new DemoTest();

        $repository->deactivate($nonexistentDemoTest);
    }

}
