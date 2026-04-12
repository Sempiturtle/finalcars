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

        // Sample Rewards
        \App\Models\Reward::updateOrCreate(
            ['name' => '5% OFF Oil Change'],
            [
                'description' => 'Get 5% off on your next oil change service.',
                'points_cost' => 500,
                'is_active' => true,
            ]
        );

        \App\Models\Reward::updateOrCreate(
            ['name' => 'Free Tire Rotation'],
            [
                'description' => 'Claim a free tire rotation for any vehicle in your fleet.',
                'points_cost' => 1000,
                'is_active' => true,
            ]
        );

        \App\Models\Reward::updateOrCreate(
            ['name' => '15% OFF Full Service'],
            [
                'description' => 'A significant discount for your vehicle\'s comprehensive full service check.',
                'points_cost' => 2500,
                'is_active' => true,
            ]
        );
    }
}
