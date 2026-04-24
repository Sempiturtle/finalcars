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

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::created(function ($serviceType) {
            Reward::create([
                'service_type_id' => $serviceType->id,
                'name'            => $serviceType->name,
                'description'     => $serviceType->description ?: "Exclusive promo for {$serviceType->name} service.",
                'points_cost'     => $serviceType->promo_points_cost ?? 100,
                'is_active'       => true,
            ]);
        });

        static::updated(function ($serviceType) {
            $reward = Reward::where('service_type_id', $serviceType->id)->first();
            if ($reward) {
                $reward->update([
                    'name'        => $serviceType->name,
                    'description' => $serviceType->description ?: "Exclusive promo for {$serviceType->name} service.",
                    'points_cost' => $serviceType->promo_points_cost,
                ]);
            }
        });

        static::deleted(function ($serviceType) {
            Reward::where('service_type_id', $serviceType->id)->delete();
        });
    }
}
