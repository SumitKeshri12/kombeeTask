<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {
        $cities = [
            // California cities
            ['state_id' => 1, 'name' => 'Los Angeles'],
            ['state_id' => 1, 'name' => 'San Francisco'],
            ['state_id' => 1, 'name' => 'San Diego'],
            
            // New York cities
            ['state_id' => 2, 'name' => 'New York City'],
            ['state_id' => 2, 'name' => 'Buffalo'],
            ['state_id' => 2, 'name' => 'Albany'],
            
            // Texas cities
            ['state_id' => 3, 'name' => 'Houston'],
            ['state_id' => 3, 'name' => 'Dallas'],
            ['state_id' => 3, 'name' => 'Austin']
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
} 