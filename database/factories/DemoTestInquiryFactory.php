<?php

namespace Database\Factories;

use App\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemoTest>
 */
class DemoTestInquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payload = [];
        for ($i = 0; $i < 2; $i++) {
            $payload[] = [
                'ref' => $this->faker->unique()->bothify('T-##'),
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
            ];
        }

        return [
            'payload' => json_encode($payload),
            'status' => InquiryStatus::Active,
            'items_total_count' => 2,
            'items_processed_count' => 0,
            'items_failed_count' => 0,
        ];
    }
}
