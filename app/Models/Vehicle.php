<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'make',
        'model',
        'year',
        'color',
        'owner_name',
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
}
