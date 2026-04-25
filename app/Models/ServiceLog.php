<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_type',
        'service_mode',
        'cost',
        'status',
        'service_date',
        'mechanic_name',
        'notes',
        'verification_photo',
        'completed_by_id',
    ];

    protected $casts = [
        'service_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getPointsEarnedAttribute()
    {
        if ($this->status !== 'completed') {
            return 0;
        }
        
        $serviceType = \App\Models\ServiceType::whereRaw('LOWER(name) = ?', [strtolower($this->service_type)])->first();
        if ($serviceType) {
            return $serviceType->points_awarded;
        }

        return floor(($this->cost ?? 0) / 10);
    }
}
