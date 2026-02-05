<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmailLog;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;

class EmailNotificationController extends Controller
{
    /**
     * Display a listing of email notifications and logs.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);

        // Statistics
        $stats = [
            'total_sent' => EmailLog::count(),
            'successfully_delivered' => EmailLog::where('status', 'delivered')->count(),
            'failed_pending' => EmailLog::whereIn('status', ['failed', 'pending'])->count(),
        ];

        // Vehicles Requiring Notifications (Overdue or Due Soon)
        $vehiclesRequiringAttention = Vehicle::where('next_service_date', '<', $nextWeek)
            ->where('status', '!=', 'inactive')
            ->orderBy('next_service_date', 'asc')
            ->get()
            ->map(function ($vehicle) use ($today) {
                // Try to find the user by owner_name if user_id is null (for backward compatibility)
                $email = $vehicle->owner ? $vehicle->owner->email : null;
                if (!$email) {
                    $user = User::where('name', $vehicle->owner_name)->first();
                    $email = $user ? $user->email : 'no-email@example.com';
                }

                $isOverdue = Carbon::parse($vehicle->next_service_date)->isPast();
                
                return [
                    'id' => $vehicle->id,
                    'plate_number' => $vehicle->plate_number,
                    'customer_name' => $vehicle->owner_name,
                    'customer_email' => $email,
                    'vehicle_desc' => "{$vehicle->make} {$vehicle->model}",
                    'next_service' => $vehicle->next_service_date->format('m/d/Y'),
                    'type' => $isOverdue ? 'Overdue Notice' : 'Maintenance Reminder',
                    'is_overdue' => $isOverdue
                ];
            });

        // Email Logs with filtering
        $logsQuery = EmailLog::with('vehicle')->latest();
        
        if ($request->filled('type') && $request->type !== 'All') {
            $logsQuery->where('notification_type', strtolower(str_replace(' ', '_', $request->type)));
        }

        $emailLogs = $logsQuery->paginate(15);

        return view('admin.notifications.index', compact('stats', 'vehiclesRequiringAttention', 'emailLogs'));
    }

    /**
     * Send a notification email to the vehicle owner.
     */
    public function send(Request $request, Vehicle $vehicle)
    {
        // For simulation, we'll just log the email as sent
        $user = $vehicle->owner ?? User::where('name', $vehicle->owner_name)->first();
        $email = $user ? $user->email : 'no-email@example.com';
        
        $type = Carbon::parse($vehicle->next_service_date)->isPast() ? 'overdue' : 'before_due';

        EmailLog::create([
            'vehicle_id' => $vehicle->id,
            'recipient_email' => $email,
            'notification_type' => $type,
            'status' => 'delivered',
            'sent_at' => now(),
        ]);

        return redirect()->back()->with('success', "Notification successfully sent to {$vehicle->owner_name}!");
    }
}
