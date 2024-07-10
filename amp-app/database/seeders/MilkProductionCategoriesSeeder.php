<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MilkProductionCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MilkProductionCategory::factory([
            'name' => 'production',
        ])->create();

        \App\Models\MilkProductionCategory::factory([
            'name' => 'sell',
        ])->create();
    }
}
