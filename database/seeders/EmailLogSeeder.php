<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicle = \App\Models\Vehicle::first();
        if (!$vehicle) return;

        // Ensure vehicle has a user_id for the test
        $user = \App\Models\User::where('role', 'customer')->first();
        if ($user) {
            $vehicle->update(['user_id' => $user->id]);
        }

        \App\Models\EmailLog::create([
            'vehicle_id' => $vehicle->id,
            'recipient_email' => $user ? $user->email : 'sarah.williams@email.com',
            'notification_type' => 'overdue',
            'status' => 'delivered',
            'sent_at' => now()->subDays(2),
        ]);

        \App\Models\EmailLog::create([
            'vehicle_id' => $vehicle->id,
            'recipient_email' => $user ? $user->email : 'sarah.williams@email.com',
            'notification_type' => 'before_due',
            'status' => 'delivered',
            'sent_at' => now()->subDays(10),
        ]);

        \App\Models\EmailLog::create([
            'vehicle_id' => $vehicle->id,
            'recipient_email' => $user ? $user->email : 'sarah.williams@email.com',
            'notification_type' => 'on_due',
            'status' => 'failed',
            'sent_at' => now()->subDays(5),
        ]);
    }
}
