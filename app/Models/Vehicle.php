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
    ];

    protected $casts = [
        'next_service_date' => 'date',
        'registration_date' => 'date',
        'services' => 'array',
        'total_cost' => 'decimal:2',
    ];
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
        });

        static::updated(function ($vehicle) {
            if ($vehicle->isDirty('services') || $vehicle->isDirty('mechanic_name')) {
                $vehicle->syncServiceLogs();
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
                $this->serviceLogs()->create([
                    'service_type' => $service['type'] ?? 'N/A',
                    'cost' => $service['cost'] ?? 0,
                    'status' => 'completed',
                    'service_date' => $this->updated_at ?? now(),
                    'mechanic_name' => $this->mechanic_name,
                ]);
            }
        }
    }
}
