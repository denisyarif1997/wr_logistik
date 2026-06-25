<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToExistingUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users without roles
        $usersWithoutRoles = User::whereDoesntHave('roles')->get();

        if ($usersWithoutRoles->isEmpty()) {
            $this->info('All users already have roles assigned.');
            return;
        }

        $this->info("Found {$usersWithoutRoles->count()} users without roles.");
        $this->info('Assigning default role (vendor) to users without roles...');

        // Get vendor role
        $vendorRole = Role::where('name', 'vendor')->first();

        if (!$vendorRole) {
            $this->error('Vendor role not found. Please run RoleSeeder first.');
            return;
        }

        // Assign vendor role to all users without roles
        foreach ($usersWithoutRoles as $user) {
            $user->assignRole('vendor');
            $this->line("  ✓ Assigned 'vendor' role to {$user->name} ({$user->email})");
        }

        $this->info('✓ Successfully assigned roles to all users without roles.');
        $this->info('');
        $this->info('To change a user\'s role, use:');
        $this->info('  php artisan user:assign-role');
    }
}