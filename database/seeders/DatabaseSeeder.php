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

        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff Member',
                'username' => 'staff',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'nathaniel@gmail.com'],
            [
                'name' => 'Nathaniel Amistoso',
                'username' => 'nathaniel',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        $this->call([
            VehicleSeeder::class,
        ]);
    }
}
