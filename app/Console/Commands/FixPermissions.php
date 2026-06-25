<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:fix 
                            {email? : The email of the user to fix (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix permission issues - clear cache and verify permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Fixing Permissions...');
        $this->newLine();

        // Step 1: Clear all caches
        $this->info('Step 1: Clearing caches...');
        $this->call('cache:clear');
        $this->call('permission:cache-reset');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->info('✓ All caches cleared');
        $this->newLine();

        // Step 2: Check if roles exist
        $this->info('Step 2: Checking roles...');
        $roles = Role::all();
        
        if ($roles->isEmpty()) {
            $this->error('❌ No roles found! Please run: php artisan db:seed --class=RoleSeeder');
            return 1;
        }

        $this->info('✓ Found ' . $roles->count() . ' roles:');
        foreach ($roles as $role) {
            $permCount = $role->permissions()->count();
            $this->line("  - {$role->name} ({$permCount} permissions)");
        }
        $this->newLine();

        // Step 3: Check if permissions exist
        $this->info('Step 3: Checking permissions...');
        $permissions = Permission::all();
        
        if ($permissions->isEmpty()) {
            $this->error('❌ No permissions found! Please run: php artisan db:seed --class=PermissionSeeder');
            return 1;
        }

        $this->info('✓ Found ' . $permissions->count() . ' permissions');
        $this->newLine();

        // Step 4: Check users and their roles
        $this->info('Step 4: Checking users...');
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->warn('⚠ No users found in database.');
            return 0;
        }

        $usersWithoutRoles = [];
        foreach ($users as $user) {
            $roles = $user->getRoleNames()->implode(', ') ?: 'No role';
            $permCount = $user->getAllPermissions()->count();
            $this->line("  - {$user->name} ({$user->email})");
            $this->line("    Roles: {$roles}");
            $this->line("    Permissions: {$permCount}");
            
            if ($user->getRoleNames()->isEmpty()) {
                $usersWithoutRoles[] = $user;
            }
        }
        $this->newLine();

        // Step 5: Auto-fix users without roles
        if (!empty($usersWithoutRoles)) {
            $this->warn('⚠ Found ' . count($usersWithoutRoles) . ' user(s) without roles');
            
            if ($this->confirm('Do you want to assign "vendor" role to all users without roles?')) {
                $vendorRole = Role::where('name', 'vendor')->first();
                
                if (!$vendorRole) {
                    $this->error('❌ Vendor role not found!');
                    return 1;
                }

                foreach ($usersWithoutRoles as $user) {
                    $user->assignRole('vendor');
                    $this->info("✓ Assigned 'vendor' role to {$user->name} ({$user->email})");
                }
                
                $this->newLine();
            }
        } else {
            $this->info('✓ All users have roles assigned');
            $this->newLine();
        }

        // Step 6: Verify specific user if email provided
        if ($email = $this->argument('email')) {
            $this->info('Step 5: Verifying specific user...');
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("❌ User with email '{$email}' not found.");
                return 1;
            }

            $this->info('User Details:');
            $this->line("  Name: {$user->name}");
            $this->line("  Email: {$user->email}");
            $this->line("  Roles: " . ($user->getRoleNames()->implode(', ') ?: 'None'));
            $this->line("  Permissions: " . $user->getAllPermissions()->count());
            $this->newLine();

            if ($user->getRoleNames()->isEmpty()) {
                $this->warn('⚠ This user has no role assigned!');
                
                if ($this->confirm('Do you want to assign a role now?')) {
                    $role = $this->choice('Select role', ['admin', 'vendor', 'user'], 1);
                    $user->assignRole($role);
                    $this->info("✓ Assigned '{$role}' role to {$user->name}");
                }
            } else {
                $this->info('✓ User has proper role assignment');
            }
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('✓ Permission fix completed!');
        $this->info('═══════════════════════════════════════');
        $this->newLine();
        $this->info('📋 Next Steps:');
        $this->line('1. Logout from the application');
        $this->line('2. Clear browser cache (Ctrl+Shift+R)');
        $this->line('3. Login again');
        $this->line('4. Check if menus are now visible');
        $this->newLine();
        $this->info('💡 If menus still not showing:');
        $this->line('   - Make sure you assigned the correct role');
        $this->line('   - Try running: php artisan user:assign-role');
        $this->newLine();

        return 0;
    }
}