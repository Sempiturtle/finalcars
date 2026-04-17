<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Transmission Service', 'description' => 'Transmission fluid change and inspection'],
            ['name' => 'General Inspection', 'description' => 'Comprehensive vehicle safety inspection'],
            ['name' => 'Oil Change', 'description' => 'Regular oil change and filter replacement'],
            ['name' => 'Brake Inspection', 'description' => 'Complete brake system inspection'],
            ['name' => 'Engine Tune-up', 'description' => 'Full engine diagnostic and tune-up'],
            ['name' => 'Regular Maintenance', 'description' => 'Regular Maintenance service'],
        ];

        foreach ($types as $type) {
            ServiceType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
