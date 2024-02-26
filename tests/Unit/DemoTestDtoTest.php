<?php

namespace Tests\Unit\DataTransferObjects;

use Tests\TestCase;
use App\DataTransferObjects\DemoTestDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class DemoTestDtoTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_collection_of_demo_test_dto_from_json()
    {
        $json = json_encode([
            [
                'ref' => 'T1', 'name' => 'Test 1',
                'description' => 'Description 1'
            ],
            [
                'ref' => 'T2', 'name' => 'Test 2'
            ]
        ]);

        $dtos = DemoTestDto::fromJsonArray($json);

        $this->assertInstanceOf(Collection::class, $dtos);
        $this->assertCount(2, $dtos);
        $this->assertInstanceOf(DemoTestDto::class, $dtos->first());
        $this->assertEquals('T1', $dtos->first()->ref);
        $this->assertEquals('Test 2', $dtos->last()->name);
        $this->assertNull($dtos->last()->description);
    }

    public function test_it_throws_exception_for_invalid_json()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid payload provided.');

        $invalidJson = 'invalid json';

        DemoTestDto::fromJsonArray($invalidJson);
    }
}
