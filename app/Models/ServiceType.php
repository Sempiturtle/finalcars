<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_cost',
        'points_awarded',
        'promo_points_cost',
    ];

    protected $casts = [
        'base_cost' => 'decimal:2',
    ];

    // Automatic reward creation removed to allow manual control.
}
