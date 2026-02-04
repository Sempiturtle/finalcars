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
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Staff Member',
            'email' => 'staff@gmail.com',
            'username' => 'staff',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'staff',
        ]);

    }
}
