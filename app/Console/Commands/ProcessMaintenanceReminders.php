<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessMaintenanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:process-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Generate AI professional reminders and trigger automated follow-up calls for overdue vehicles.';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\AIReminderService $aiService)
    {
        $this->info("Processing Maintenance Reminders...");

        // 1. Generate AI Reminders for Due/Overdue vehicles (Not notified in last 7 days)
        $vehiclesToNotify = \App\Models\Vehicle::where('next_service_date', '<', now()->addDays(7))
            ->where(function ($query) {
                $query->whereNull('last_notification_at')
                      ->orWhere('last_notification_at', '<', now()->subDays(7));
            })->get();

        foreach ($vehiclesToNotify as $vehicle) {
            $this->info("Generating AI message for: {$vehicle->plate_number}");
            $message = $aiService->generateReminder($vehicle);
            
            $vehicle->update([
                'last_ai_message' => $message,
                'last_notification_at' => now(),
            ]);

            // Here you would typically send an Email/SMS with this AI message
            // Mail::to($vehicle->owner_email)->send(new MaintenanceReminder($message));
        }

        // 2. Trigger Automated Call Follow-up for Critical Overdue (5+ days)
        // Only if they were notified at least 48 hours ago and still haven't responded
        $criticalVehicles = \App\Models\Vehicle::criticalOverdue()
            ->whereNotNull('last_notification_at')
            ->where('last_notification_at', '<', now()->subHours(48))
            ->get();

        foreach ($criticalVehicles as $vehicle) {
            $this->warn("Initiating Follow-up Call for: {$vehicle->plate_number}");
            
            $notification = new \App\Notifications\OverdueFollowUpCall($vehicle->last_ai_message);
            $sid = $notification->triggerCall($vehicle->owner_phone ?? 'Verified_Twilio_Number');

            \App\Models\CallLog::create([
                'vehicle_id' => $vehicle->id,
                'phone_number' => $vehicle->owner_phone ?? 'Verified_Twilio_Number',
                'sid' => $sid,
                'status' => 'initiated',
                'tts_content' => $vehicle->last_ai_message,
            ]);

            // Update last_notification_at so we don't call them again too soon
            $vehicle->update(['last_notification_at' => now()]);
        }

        $this->info("Maintenance reminder processing completed.");
    }
}
