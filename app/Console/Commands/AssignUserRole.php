<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignUserRole extends Command
{
    protected $signature = 'role:assign-user {email}';
    protected $description = 'Assign User role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $role = Role::where('name', 'User')->first();
        
        if (!$role) {
            $this->error('User role not found! Please run php artisan db:seed --class=RoleSeeder first.');
            return 1;
        }

        $user->assignRole($role);
        $this->info("Successfully assigned User role to user {$email}");
        
        return 0;
    }
} 