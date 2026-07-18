<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HutangSupplierPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create hutang_supplier permission
        Permission::create(['name' => 'hutang_supplier', 'guard_name' => 'web']);

        // Assign to admin role if exists
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo('hutang_supplier');
        }

        // Assign to superadmin role if exists
        $superadminRole = Role::where('name', 'superadmin')->first();
        if ($superadminRole) {
            $superadminRole->givePermissionTo('hutang_supplier');
        }
    }
}