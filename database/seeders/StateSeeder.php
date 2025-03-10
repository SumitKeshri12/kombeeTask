<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['name' => 'California'],
            ['name' => 'New York'],
            ['name' => 'Texas'],
            ['name' => 'Florida'],
            ['name' => 'Illinois']
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
} 