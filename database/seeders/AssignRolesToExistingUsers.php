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
            echo "All users already have roles assigned.\n";
            return;
        }

        echo "Found {$usersWithoutRoles->count()} users without roles.\n";
        echo "Assigning default role (vendor) to users without roles...\n";

        // Get vendor role
        $vendorRole = Role::where('name', 'vendor')->first();

        if (!$vendorRole) {
            echo "Vendor role not found. Please run RoleSeeder first.\n";
            return;
        }

        // Assign vendor role to all users without roles
        foreach ($usersWithoutRoles as $user) {
            $user->assignRole('vendor');
            echo "  ✓ Assigned 'vendor' role to {$user->name} ({$user->email})\n";
        }

        echo "✓ Successfully assigned roles to all users without roles.\n";
        echo "\n";
        echo "To change a user's role, use:\n";
        echo "  php artisan user:assign-role\n";
    }
}