<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class MilkProductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\MilkProductionCategory::factory(),
            'location_id' => \App\Models\Location::factory(),
            'comments' => fake()->sentence(),
            'quantity' => fake()->numberBetween(5000, 9999999),
            'sell_price' => fake()->numberBetween(100, 2000),
            'sell_amount' => fake()->numberBetween(100, 2000),
        ];
    }
}
