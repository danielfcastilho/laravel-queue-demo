<?php

namespace Tests\Unit\DataTransferObjects;

use App\Http\Requests\ProcessDemoTestRequest;
use App\Models\DemoTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProcessDemoTestRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_passes_validation_for_valid_payload()
    {
        $payload = [
            ['ref' => 'T1', 'name' => 'Test 1', 'description' => 'Description 1'],
        ];

        $request = new ProcessDemoTestRequest();
        $request->merge($payload);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertFalse($validator->fails());
    }


    public function test_it_fails_validation_when_ref_is_not_active()
    {
        $demoTest = DemoTest::factory()->create(['is_active' => false]);

        $payload = [
            [
                'ref' => $demoTest->ref,
                'name' => $demoTest->name,
                'description' => $demoTest->description
            ],
        ];

        $request = new ProcessDemoTestRequest();
        $request->merge($payload);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertNotEmpty($validator->errors()->first('*.ref'));
    }

    public function test_it_responds_with_custom_error_message_on_failed_validation()
    {
        $payload = [
            ['ref' => 'T1', 'name' => 'Test 1', 'description' => 'Description 1'],
            ['ref' => 'T1', 'name' => 'Test 1', 'description' => 'Description 1'], //duplicate ref
            ['ref' => 'T1', 'description' => 'Description 1'], //missing name
        ];

        $response = $this->json('POST', '/api/demo/test', $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The 0.ref field has a duplicate value.',
        ]);
    }
}
