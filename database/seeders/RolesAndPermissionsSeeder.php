<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Product permissions
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Order permissions
            'view orders',
            'create orders',
            'edit orders',
            'cancel orders',
            
            // Invoice permissions
            'view invoices',
            'generate invoices',
            
            // Payment permissions
            'view payments',
            'verify payments',
            'reject payments',
            
            // Warranty permissions
            'view warranties',
            'extend warranties',
            
            // User management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Settings permissions
            'view settings',
            'edit settings',
            
            // Discount permissions
            'view discounts',
            'create discounts',
            'edit discounts',
            'delete discounts',
            
            // Report permissions
            'view reports',
            
            // Audit log permissions
            'view audit logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Manager role (can do everything)
        $managerRole = SpatieRole::create(['name' => 'manager']);
        $managerRole->givePermissionTo(Permission::all());

        // Admin role (limited administrative tasks)
        $adminRole = SpatieRole::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view products',
            'create products',
            'edit products',
            'view orders',
            'edit orders',
            'view invoices',
            'generate invoices',
            'view payments',
            'verify payments',
            'reject payments',
            'view warranties',
            'view discounts',
            'create discounts',
            'edit discounts',
            'view reports',
        ]);

        // Customer role (very limited permissions)
        $customerRole = SpatieRole::create(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view products',
            'create orders',
            'view orders',
            'cancel orders',
            'view invoices',
            'view payments',
            'view warranties',
            'extend warranties',
        ]);

        // Create default users
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@dapurkode.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $manager->assignRole($managerRole);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@dapurkode.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole($customerRole);
    }
}
