<?php

namespace App\Repositories;

use App\DataTransferObjects\DemoTestDto;
use App\Models\DemoTest;

interface DemoTestRepositoryInterface
{
    /**
     * Create or update a DemoTest entity from a DTO.
     * 
     * @param  DemoTestDto  $demoTestDto
     * @return DemoTest
     * @throws \Exception
     */
    public function createOrUpdate(DemoTestDto $demoTestDto): DemoTest;

    /**
     * Activate a specified DemoTest entity.
     * 
     * @param  DemoTestDto  $demoTestDto
     * @return bool
     * @throws \Exception
     */
    public function activate(DemoTest $demoTest): bool;

    /**
     * Deactivate a specified DemoTest entity.
     * 
     * @param  DemoTestDto  $demoTestDto
     * @return bool
     * @throws \Exception
     */
    public function deactivate(DemoTest $demoTest): bool;
}
