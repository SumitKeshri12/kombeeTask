<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User permissions
            ['name' => 'view-users', 'guard_name' => 'api'],
            ['name' => 'create-users', 'guard_name' => 'api'],
            ['name' => 'edit-users', 'guard_name' => 'api'],
            ['name' => 'delete-users', 'guard_name' => 'api'],
            
            // Supplier permissions
            ['name' => 'view-suppliers', 'guard_name' => 'api'],
            ['name' => 'create-suppliers', 'guard_name' => 'api'],
            ['name' => 'edit-suppliers', 'guard_name' => 'api'],
            ['name' => 'delete-suppliers', 'guard_name' => 'api'],
            
            // Customer permissions
            ['name' => 'view-customers', 'guard_name' => 'api'],
            ['name' => 'create-customers', 'guard_name' => 'api'],
            ['name' => 'edit-customers', 'guard_name' => 'api'],
            ['name' => 'delete-customers', 'guard_name' => 'api'],
            
            // Role permissions
            ['name' => 'view-roles', 'guard_name' => 'api'],
            ['name' => 'create-roles', 'guard_name' => 'api'],
            ['name' => 'edit-roles', 'guard_name' => 'api'],
            ['name' => 'delete-roles', 'guard_name' => 'api']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 