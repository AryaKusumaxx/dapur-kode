<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the specific seeders in order
        $this->call([
            RolesAndPermissionsSeeder::class,
            ProductSeeder::class,
            SettingSeeder::class,
            DiscountSeeder::class,
            UserSeeder::class,
        ]);
    }
}
