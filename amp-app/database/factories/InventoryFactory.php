<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'category_id' => \App\Models\Category::factory(),
            'inventorie_type' => \App\Models\Inventorie_type::factory(),
            'details' => fake()->sentence(),
            'serial' => fake()->sentence(),
            'color' => fake()->sentence(),
            'shade_no' => fake()->buildingNumber(),
            'value_amount' => fake()->numberBetween(5000, 9999999),
        ];
    }
}
