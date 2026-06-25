<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role 
                            {email? : The email of the user}
                            {role? : The role to assign (admin/vendor/user)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role to existing user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        // If no email provided, show all users
        if (!$email) {
            $users = User::all();
            
            if ($users->isEmpty()) {
                $this->error('No users found in database.');
                return 1;
            }

            $this->info('Available users:');
            foreach ($users as $user) {
                $roles = $user->getRoleNames()->implode(', ') ?: 'No role assigned';
                $this->line("  - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Roles: {$roles}");
            }

            $email = $this->ask('Enter the email of the user you want to assign role to');
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // If no role provided, ask for it
        if (!$role) {
            $this->info('Available roles:');
            $this->line('  - admin (full access)');
            $this->line('  - vendor (transaction access)');
            $this->line('  - user (read-only access)');
            
            $role = $this->choice(
                'Select role to assign to ' . $user->name,
                ['admin', 'vendor', 'user'],
                0
            );
        }

        // Validate role exists
        if (!in_array($role, ['admin', 'vendor', 'user'])) {
            $this->error("Invalid role '{$role}'. Available roles: admin, vendor, user");
            return 1;
        }

        // Check if role exists in database
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            $this->error("Role '{$role}' not found in database. Please run: php artisan db:seed --class=RoleSeeder");
            return 1;
        }

        // Assign role
        $user->syncRoles([$role]);

        $this->info("✓ Successfully assigned '{$role}' role to {$user->name} ({$user->email})");
        $this->info('Permissions assigned: ' . $user->getAllPermissions()->count() . ' permissions');

        return 0;
    }
}