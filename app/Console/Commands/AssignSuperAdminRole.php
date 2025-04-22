<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignSuperAdminRole extends Command
{
    protected $signature = 'role:assign-super-admin {email}';
    protected $description = 'Assign Super Admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $role = Role::where('name', 'Super Admin')->first();
        
        if (!$role) {
            $this->error('Super Admin role not found! Please run php artisan db:seed --class=RoleSeeder first.');
            return 1;
        }

        $user->assignRole($role);
        $this->info("Successfully assigned Super Admin role to user {$email}");
        
        return 0;
    }
} 