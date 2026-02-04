<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@autocheck.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin',
        ]);

        \App\Models\Vehicle::create([
            'plate_number' => 'ABC-1234',
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => '2020',
            'color' => 'Silver',
            'owner_name' => 'Robert Johnson',
            'next_service_date' => '2026-02-15',
            'registration_date' => '2026-01-15',
            'status' => 'active',
        ]);
    }
}
