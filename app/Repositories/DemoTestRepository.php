<?php

namespace App\Repositories;

use App\DataTransferObjects\DemoTestDto;
use App\Enums\DemoTestStatus;
use App\Models\DemoTest;
use Illuminate\Support\Facades\DB;

class DemoTestRepository implements DemoTestRepositoryInterface
{
    /**
     * Create or update a DemoTest entity from a DTO.
     * 
     * @param  DemoTestDto  $demoTestDto
     * @return DemoTest
     * @throws \Exception
     */
    public function createOrUpdate(DemoTestDto $demoTestDto): DemoTest
    {
        try {
            return DB::transaction(function () use ($demoTestDto) {
                $demoTest = DemoTest::where('ref', $demoTestDto->ref)->first();

                if (!$demoTest) {
                    $demoTest = new DemoTest();
                    $demoTest->ref = $demoTestDto->ref;
                    $demoTest->status = DemoTestStatus::New->value;
                } else {
                    $demoTest->status = DemoTestStatus::Updated->value;
                }

                $demoTest->name = $demoTestDto->name;
                $demoTest->description = $demoTestDto->description;
                $demoTest->save();

                return $demoTest;
            });
        } catch (\Exception $e) {
            throw new \Exception("Failed to create or update DemoTest: " . $e->getMessage());
        }
    }

    /**
     * Activate a specified DemoTest entity.
     * 
     * @param  DemoTestDto  $demoTestDto
     * @return bool
     * @throws \Exception
     */
    public function activate(DemoTest $demoTest): bool
    {
        try {
            $demoTest->is_active = true;
            return $demoTest->save();
        } catch (\Exception $e) {
            throw new \Exception("Failed to activate DemoTest: " . $e->getMessage());
        }
    }

    /**
     * Deactivate a specified DemoTest entity.
     * 
     * @param  DemoTestDto  $demoTestDto
     * @return bool
     * @throws \Exception
     */
    public function deactivate(DemoTest $demoTest): bool
    {
        try {
            $demoTest->is_active = false;
            return $demoTest->save();
        } catch (\Exception $e) {
            throw new \Exception("Failed to deactivate DemoTest: " . $e->getMessage());
        }
    }
}
