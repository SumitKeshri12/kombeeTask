<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Get all permissions
        $permissions = Permission::all();

        // Get the Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        
        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions($permissions);

        // Get the Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        
        // Assign specific permissions to Admin
        $adminPermissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-suppliers',
            'create-suppliers',
            'edit-suppliers',
            'delete-suppliers',
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
        ];
        $adminRole->syncPermissions($adminPermissions);

        // Get the User role
        $userRole = Role::where('name', 'User')->first();
        
        // Assign basic permissions to User
        $userPermissions = [
            'view-suppliers',
            'view-customers',
        ];
        $userRole->syncPermissions($userPermissions);
    }
} 