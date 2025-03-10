<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User permissions
            ['name' => 'view-users', 'guard_name' => 'web'],
            ['name' => 'create-users', 'guard_name' => 'web'],
            ['name' => 'edit-users', 'guard_name' => 'web'],
            ['name' => 'delete-users', 'guard_name' => 'web'],
            
            // Supplier permissions
            ['name' => 'view-suppliers', 'guard_name' => 'web'],
            ['name' => 'create-suppliers', 'guard_name' => 'web'],
            ['name' => 'edit-suppliers', 'guard_name' => 'web'],
            ['name' => 'delete-suppliers', 'guard_name' => 'web'],
            
            // Customer permissions
            ['name' => 'view-customers', 'guard_name' => 'web'],
            ['name' => 'create-customers', 'guard_name' => 'web'],
            ['name' => 'edit-customers', 'guard_name' => 'web'],
            ['name' => 'delete-customers', 'guard_name' => 'web'],
            
            // Role permissions
            ['name' => 'view-roles', 'guard_name' => 'web'],
            ['name' => 'create-roles', 'guard_name' => 'web'],
            ['name' => 'edit-roles', 'guard_name' => 'web'],
            ['name' => 'delete-roles', 'guard_name' => 'web']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 