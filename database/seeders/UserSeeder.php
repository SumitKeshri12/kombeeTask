<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'contact_number' => '1234567890',
            'postcode' => '12345',
            'city_id' => 1,
            'gender' => 'male',
            'hobbies' => ['reading', 'coding']
        ]);

        // Assign super admin role using Spatie's permission package
        $superAdmin->assignRole('Super Admin');

        // Create some regular users
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'contact_number' => '1234567891',
                'postcode' => '12346',
                'city_id' => 2,
                'gender' => 'male',
                'hobbies' => ['sports', 'music']
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'contact_number' => '1234567892',
                'postcode' => '12347',
                'city_id' => 3,
                'gender' => 'female',
                'hobbies' => ['painting', 'dancing']
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole('User');
        }
    }
} 