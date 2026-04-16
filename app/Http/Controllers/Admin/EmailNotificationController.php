<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmailLog;
use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\OverdueFollowUpCall;
use App\Notifications\SystemNotification;
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

        // Notify User in Bell
        if ($user) {
            $icon = '<path stroke-linecap = "round" stroke-linejoin = "round" stroke-width = "2" d = "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
            $user->notify(new SystemNotification(
                "Maintenance Email Sent",
                "An official maintenance reminder email regarding your vehicle ({$vehicle->plate_number}) has been sent to your inbox.",
                $icon
            ));
        }

        return redirect()->back()->with('success', "Notification successfully sent to {$vehicle->owner_name}!");
    }

    /**
     * Display all vehicles requiring attention (overdue).
     */
    public function attentionRequired()
    {
        $today = Carbon::today();
        
        $attentionRequired = Vehicle::where('next_service_date', '<', $today)
            ->where('status', '!=', 'inactive')
            ->orderBy('next_service_date', 'asc')
            ->get()
            ->map(function ($vehicle) use ($today) {
                $nextService = Carbon::parse($vehicle->next_service_date);
                $user = $vehicle->owner ?? User::where('name', $vehicle->owner_name)->first();
                
                return [
                    'id' => $vehicle->id,
                    'plate_number' => $vehicle->plate_number,
                    'make_model' => "{$vehicle->make} {$vehicle->model}",
                    'customer_name' => $vehicle->owner_name,
                    'customer_email' => $user ? $user->email : 'no-email@example.com',
                    'customer_phone' => $user ? $user->phone : null,
                    'days_overdue' => $today->diffInDays($nextService),
                    'next_service_date' => $nextService->format('M d, Y'),
                ];
            });

        return view('admin.notifications.attention-required', compact('attentionRequired'));
    }

    /**
     * Notify all owners of overdue vehicles.
     */
    public function notifyAll()
    {
        $today = Carbon::today();
        $overdueVehicles = Vehicle::where('next_service_date', '<', $today)
            ->where('status', '!=', 'inactive')
            ->get();

        if ($overdueVehicles->isEmpty()) {
            return redirect()->back()->with('info', "No overdue vehicles to notify.");
        }

        foreach ($overdueVehicles as $vehicle) {
            $user = $vehicle->owner ?? User::where('name', $vehicle->owner_name)->first();
            $email = $user ? $user->email : 'no-email@example.com';

            EmailLog::create([
                'vehicle_id' => $vehicle->id,
                'recipient_email' => $email,
                'notification_type' => 'overdue',
                'status' => 'delivered',
                'sent_at' => now(),
            ]);

            // Notify User in Bell
            if ($user) {
                $icon = '<path stroke-linecap = "round" stroke-linejoin = "round" stroke-width = "2" d = "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
                $user->notify(new SystemNotification(
                    "Overdue Maintenance Notice",
                    "An automated email has been sent because your vehicle ({$vehicle->plate_number}) is overdue for service.",
                    $icon
                ));
            }
        }

        return redirect()->back()->with('success', "Notifications sent to owners of " . $overdueVehicles->count() . " overdue vehicles!");
    }

    /**
     * Trigger a Twilio automated AI call for a specific vehicle owner.
     */
    public function call(Request $request, Vehicle $vehicle)
    {
        $user = $vehicle->owner ?? User::where('name', $vehicle->owner_name)->first();
        
        if (!$user || !$user->phone) {
            return redirect()->back()->with('error', "No phone number found for this customer. Please update their profile.");
        }

        $plate = $vehicle->plate_number;
        $customer = $user->name ?? $vehicle->owner_name;
        
        $date = Carbon::parse($vehicle->next_service_date)->format('M d, Y');
        $message = "Hello {$customer}, this is an automated reminder from your car service center. Your vehicle with plate number {$plate} is overdue for maintenance since {$date}. Please schedule your service as soon as possible.";

        try {
            $notification = new OverdueFollowUpCall($message);
            $sid = $notification->triggerCall($user->phone);

            if ($sid !== 'FAILED' && $sid !== 'SIMULATED_SID') {
                if ($user) {
                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />';
                    $user->notify(new SystemNotification(
                        "Automated Call Initiated",
                        "An AI follow-up call regarding your vehicle ({$vehicle->plate_number}) is being connected to your phone.",
                        $icon
                    ));
                }
                return redirect()->back()->with('success', "AI Call successfully initiated to {$user->phone}. SID: {$sid}");
            } elseif ($sid === 'SIMULATED_SID') {
                if ($user) {
                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />';
                    $user->notify(new SystemNotification(
                        "Automated Call (Simulated)",
                        "A simulated follow-up call is being logged for your vehicle ({$vehicle->plate_number}).",
                        $icon
                    ));
                }
                return redirect()->back()->with('info', "Simulation: Call would be sent to {$user->phone} with message: \"{$message}\"");
            } else {
                return redirect()->back()->with('error', "Twilio call failed. Check your configuration or Twilio console.");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Error triggering call: " . $e->getMessage());
        }
    }
}
