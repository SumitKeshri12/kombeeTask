<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'Retail Store A',
                'email' => 'store.a@example.com',
                'phone' => '1234567890',
                'address' => '123 Retail Street, Los Angeles, CA'
            ],
            [
                'name' => 'Business Corp B',
                'email' => 'corp.b@example.com',
                'phone' => '0987654321',
                'address' => '456 Business Road, New York, NY'
            ],
            [
                'name' => 'Shop C',
                'email' => 'shop.c@example.com',
                'phone' => '1122334455',
                'address' => '789 Shop Avenue, Chicago, IL'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 