<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\EmailLog;
use App\Mail\MaintenanceReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    /**
     * Send a test maintenance reminder to a specific user.
     */
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Find a vehicle for this user to make the email realistic
        $vehicle = Vehicle::where('user_id', $user->id)->first();
        
        if (!$vehicle) {
            // If no vehicle associated with the user model, try finding one by owner_name
            $vehicle = Vehicle::where('owner_name', $user->name)->first();
        }

        if (!$vehicle) {
            // Fallback to any vehicle or a dummy one for testing purposes
            $vehicle = Vehicle::first() ?: new Vehicle([
                'make' => 'Sample',
                'model' => 'Vehicle',
                'plate_number' => 'TEST-123',
                'owner_name' => $user->name,
                'next_service_date' => now()->addDay(),
            ]);
        }

        try {
            Mail::to($user->email)->send(new MaintenanceReminder($vehicle));

            EmailLog::create([
                'vehicle_id' => $vehicle->id ?? null,
                'recipient_email' => $user->email,
                'notification_type' => 'test_reminder',
                'status' => 'delivered',
                'sent_at' => now(),
            ]);

            return redirect()->back()->with('success', "Test email successfully sent to {$user->email}!");
        } catch (\Exception $e) {
            EmailLog::create([
                'vehicle_id' => $vehicle->id ?? null,
                'recipient_email' => $user->email,
                'notification_type' => 'test_reminder',
                'status' => 'failed',
                'sent_at' => now(),
            ]);

            return redirect()->back()->with('error', "Failed to send test email: " . $e->getMessage());
        }
    }
}
