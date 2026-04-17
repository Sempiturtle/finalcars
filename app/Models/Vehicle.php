<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plate_number',
        'make',
        'model',
        'year',
        'color',
        'owner_name',
        'mechanic_name',
        'next_service_date',
        'registration_date',
        'status',
        'services',
        'total_cost',
        'last_ai_message',
        'last_notification_at',
    ];

    protected $casts = [
        'next_service_date' => 'date',
        'registration_date' => 'date',
        'services' => 'array',
        'total_cost' => 'decimal:2',
        'last_notification_at' => 'datetime',
    ];

    /**
     * Scope for vehicles that are 5 or more days overdue.
     */
    public function scopeCriticalOverdue($query)
    {
        return $query->where('next_service_date', '<=', now()->subDays(5));
    }

    /**
     * Check if the vehicle is 5 or more days overdue.
     */
    public function isCriticalOverdue()
    {
        if (!$this->next_service_date) return false;
        return $this->next_service_date->lte(now()->subDays(5));
    }
    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::created(function ($vehicle) {
            $vehicle->syncServiceLogs();
            
            // Notify owner of new vehicle registration
            if ($vehicle->owner) {
                $vehicle->owner->notify(new \App\Notifications\SystemNotification(
                    'New Vehicle Registered',
                    "A new {$vehicle->make} {$vehicle->model} (Plate: {$vehicle->plate_number}) has been added to your account.",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    route('customer.dashboard')
                ));
            }
        });

        static::updated(function ($vehicle) {
            if ($vehicle->isDirty('services') || $vehicle->isDirty('mechanic_name')) {
                $vehicle->syncServiceLogs();
            }

            // Notify owner of vehicle reassignment
            if ($vehicle->isDirty('user_id') && $vehicle->owner) {
                $vehicle->owner->notify(new \App\Notifications\SystemNotification(
                    'Vehicle Reassigned To You',
                    "The {$vehicle->make} {$vehicle->model} (Plate: {$vehicle->plate_number}) is now under your management.",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />',
                    route('customer.dashboard')
                ));
            }
        });
    }

    /**
     * Synchronize the services JSON array with the ServiceLog table.
     */
    public function syncServiceLogs()
    {
        // For simplicity and to maintain 1:1 sync with the fleet record's service list
        $this->serviceLogs()->delete();

        if (is_array($this->services)) {
            foreach ($this->services as $service) {
                $type = $service['type'] ?? 'N/A';
                
                $this->serviceLogs()->create([
                    'service_type' => $type,
                    'cost' => $service['cost'] ?? 0,
                    'status' => 'completed',
                    'service_date' => $this->updated_at ?? now(),
                    'mechanic_name' => $this->mechanic_name,
                ]);
            }
        }

        if ($this->owner) {
            $this->owner->recalculateLoyaltyPoints();
        }
    }
}
