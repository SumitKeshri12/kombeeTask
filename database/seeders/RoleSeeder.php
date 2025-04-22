<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin', 'guard_name' => 'api'],
            ['name' => 'Admin', 'guard_name' => 'api'],
            ['name' => 'User', 'guard_name' => 'api'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
