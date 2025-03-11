<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin', 'guard_name' => 'web', 'slug' => 'super-admin'],
            ['name' => 'Admin', 'guard_name' => 'web', 'slug' => 'admin'],
            ['name' => 'User', 'guard_name' => 'web', 'slug' => 'user'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
