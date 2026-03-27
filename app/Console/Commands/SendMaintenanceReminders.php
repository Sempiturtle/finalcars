<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\EmailLog;
use App\Mail\MaintenanceReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendMaintenanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send maintenance reminders to vehicle owners 1 day before their scheduled service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        $this->info("Checking for maintenance scheduled for: " . $tomorrow->format('Y-m-d'));

        $vehicles = Vehicle::whereDate('next_service_date', $tomorrow)
            ->where('status', '!=', 'inactive')
            ->get();

        if ($vehicles->isEmpty()) {
            $this->info("No maintenance scheduled for tomorrow.");
            return;
        }

        $count = 0;
        foreach ($vehicles as $vehicle) {
            $user = $vehicle->owner ?? User::where('name', $vehicle->owner_name)->first();
            $email = $user ? $user->email : null;

            if (!$email) {
                $this->warn("No email found for vehicle {$vehicle->plate_number} (Owner: {$vehicle->owner_name})");
                continue;
            }

            try {
                Mail::to($email)->send(new MaintenanceReminder($vehicle));

                EmailLog::create([
                    'vehicle_id' => $vehicle->id,
                    'recipient_email' => $email,
                    'notification_type' => 'reminder',
                    'status' => 'delivered',
                    'sent_at' => now(),
                ]);

                $this->info("Reminder sent to {$email} for vehicle {$vehicle->plate_number}");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$email}: " . $e->getMessage());
                
                EmailLog::create([
                    'vehicle_id' => $vehicle->id,
                    'recipient_email' => $email,
                    'notification_type' => 'reminder',
                    'status' => 'failed',
                    'sent_at' => now(),
                ]);
            }
        }

        $this->info("Completed. Total reminders sent: {$count}");
    }
}
