<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\SystemNotification;

class CheckVehicleOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-vehicle-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for vehicles approaching maintenance or overdue and notify owners.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();
        $in5Days = now()->addDays(5)->startOfDay();
        $in1Day = now()->addDays(1)->startOfDay();

        // 5 Days Before
        Vehicle::whereDate('next_service_date', $in5Days)
            ->where('status', '!=', 'inactive')
            ->get()
            ->each(fn($v) => $this->notifyOwner($v, "Maintenance Reminder (5 Days)", "Your vehicle ({$v->plate_number}) is due for service in 5 days."));

        // 1 Day Before
        Vehicle::whereDate('next_service_date', $in1Day)
            ->where('status', '!=', 'inactive')
            ->get()
            ->each(fn($v) => $this->notifyOwner($v, "Maintenance Reminder (Tomorrow)", "Your vehicle ({$v->plate_number}) is due for service tomorrow."));

        // Overdue (Today or Past)
        Vehicle::whereDate('next_service_date', '<=', $today)
            ->whereNotIn('status', ['inactive', 'in progress', 'completed'])
            ->get()
            ->each(fn($v) => $this->notifyOwner($v, "Service Overdue", "Your vehicle ({$v->plate_number}) is overdue for maintenance. Please schedule a service."));

        $this->info('Vehicle overdue checks completed.');
    }

    protected function notifyOwner(Vehicle $vehicle, $title, $message)
    {
        $user = $vehicle->owner ?? User::where('name', $vehicle->owner_name)->first();
        if ($user) {
            $icon = '<path stroke-linecap = "round" stroke-linejoin = "round" stroke-width = "2" d = "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
            
            // Check if user already has this specific notification today to avoid spam
            $alreadyNotified = $user->notifications()
                ->whereDate('created_at', now()->today())
                ->where('data->title', $title)
                ->where('data->message', 'LIKE', "%{$vehicle->plate_number}%")
                ->exists();

            if (!$alreadyNotified) {
                $user->notify(new SystemNotification($title, $message, $icon, route('customer.dashboard')));
            }
        }
    }
}
