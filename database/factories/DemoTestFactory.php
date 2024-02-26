<?php

namespace Database\Factories;

use App\Enums\DemoTestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemoTest>
 */
class DemoTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->bothify('T-##'),
            'name' => $this->faker->word,
            'description' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement(DemoTestStatus::cases()),
            'is_active' => true,
        ];
    }
}
