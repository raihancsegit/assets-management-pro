<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::factory(),
            'type_id' => \App\Models\Type::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'details' => fake()->sentence(),
            'receipt_no' => fake()->buildingNumber(),
            'amount' => fake()->numberBetween(5000, 9999999),
            'unit_value' => fake()->numberBetween(100, 2000),
            'notes' => fake()->sentence(),
            'status' => fake()->numberBetween(0, 2),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
