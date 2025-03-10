<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Tech Supplies Inc',
                'email' => 'tech@supplies.com',
                'phone' => '1234567890',
                'address' => '123 Tech Street, Silicon Valley, CA'
            ],
            [
                'name' => 'Office Solutions',
                'email' => 'office@solutions.com',
                'phone' => '0987654321',
                'address' => '456 Office Road, New York, NY'
            ],
            [
                'name' => 'Global Distributors',
                'email' => 'global@distributors.com',
                'phone' => '1122334455',
                'address' => '789 Global Avenue, Chicago, IL'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
} 