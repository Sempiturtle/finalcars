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
    ];

    protected $casts = [
        'service_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getPointsEarnedAttribute()
    {
        if ($this->status !== 'completed') {
            return 0;
        }
        
        $serviceType = \App\Models\ServiceType::where('name', $this->service_type)->first();
        if ($serviceType) {
            return $serviceType->points_awarded;
        }

        return floor(($this->cost ?? 0) / 10);
    }
}
