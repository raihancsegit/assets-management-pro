<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Inventorie_type;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $schemes = ['Deposit', 'Expanse', 'Income'];
        foreach ($schemes as $scheme) {
            (new Scheme(['name' => $scheme]))->save();
        }

        $staffRole = Role::create(['name' => 'staff']);
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);

        User::factory([
            'name' => 'staff',
            'email' => 'staff@staff.local',
            'password' => '!staff@staff.local',
        ])->create()->assignRole($staffRole);

        User::factory([
            'name' => 'admin',
            'email' => 'admin@admin.local',
            'password' => 'admin@admin.local',
        ])->create()->assignRole($adminRole);

        User::factory([
            'name' => 'manager',
            'email' => 'manager@manager.local',
            'password' => 'manager@manager.local',
        ])->create()->assignRole($managerRole);

        $types = ['OXE', 'GAVI', 'BOKNA'];
        foreach ($types as $type) {
            (new Inventorie_type(['name' => $type]))->save();
        }

        // \App\Models\Category::factory(1)->create();
        // \App\Models\Type::factory(1)->create();
        // \App\Models\Unit::factory(1)->create();
        // \App\Models\Deposit::factory(1)->create();
        // \App\Models\Expanse::factory(1)->create();
        // \App\Models\Income::factory(1)->create();
    }
}
