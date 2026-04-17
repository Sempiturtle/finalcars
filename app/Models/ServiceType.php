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

    protected static function booted()
    {
        static::created(function ($serviceType) {
            $baseName = trim(explode('(', $serviceType->name)[0]);
            $promoName = "Free " . $baseName . " Promo";

            \App\Models\Reward::firstOrCreate(
                ['name' => $promoName],
                [
                    'service_type_id' => $serviceType->id,
                    'description' => "Redeem your loyalty points for a free " . strtolower($baseName) . "!",
                    'points_cost' => $serviceType->promo_points_cost,
                    'is_active' => true,
                ]
            );
        });

        static::updated(function ($serviceType) {
            if ($serviceType->isDirty('name')) {
                $oldBaseName = trim(explode('(', $serviceType->getOriginal('name'))[0]);
                $oldPromoName = "Free " . $oldBaseName . " Promo";

                $reward = \App\Models\Reward::where('name', $oldPromoName)->first();
                if ($reward) {
                    $newBaseName = trim(explode('(', $serviceType->name)[0]);
                    $reward->update([
                        'name' => "Free " . $newBaseName . " Promo",
                        'description' => "Redeem your loyalty points for a free " . strtolower($newBaseName) . "!",
                        'service_type_id' => $serviceType->id,
                    ]);
                }
            }

            // Keep points_cost in sync if promo_points_cost changes
            if ($serviceType->isDirty('promo_points_cost')) {
                $baseName = trim(explode('(', $serviceType->name)[0]);
                $promoName = "Free " . $baseName . " Promo";
                \App\Models\Reward::where('name', $promoName)
                    ->update(['points_cost' => $serviceType->promo_points_cost]);
            }
        });

        static::deleted(function ($serviceType) {
            $baseName = trim(explode('(', $serviceType->name)[0]);
            $promoName = "Free " . $baseName . " Promo";
            \App\Models\Reward::where('name', $promoName)->delete();
        });
    }
}
