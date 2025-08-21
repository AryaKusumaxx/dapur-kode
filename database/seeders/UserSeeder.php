<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
        ]);
        $adminUser->assignRole('admin');

        // Create a manager user
        $managerUser = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'phone' => '089876543210',
            'address' => 'Jl. Manager No. 2',
        ]);
        $managerUser->assignRole('manager');

        // Create some regular customer users
        $customer1 = User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'phone' => '081122334455',
            'address' => 'Jl. Customer No. 1',
        ]);
        $customer1->assignRole('customer');

        $customer2 = User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'phone' => '082233445566',
            'address' => 'Jl. Customer No. 2',
        ]);
        $customer2->assignRole('customer');
    }
}
