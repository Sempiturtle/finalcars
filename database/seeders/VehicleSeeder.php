<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate to ensure the demo vehicle exists without wiping out user-added data
        Vehicle::updateOrCreate(
            ['plate_number' => 'LMN-9012'],
            [
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => '2023',
                'color' => 'Black',
                'owner_name' => 'Nathaniel Amistoso',
                'next_service_date' => now()->copy()->addDays(2),
                'registration_date' => now()->subMonths(6),
                'status' => 'completed',
                'mechanic_name' => 'Nathaniel Amistoso',
                'services' => [
                    ['type' => 'Oil Change (Regular oil change and filter replacement)', 'cost' => 1500.00],
                ],
                'total_cost' => 1500.00,
            ]
        );
    }
}
