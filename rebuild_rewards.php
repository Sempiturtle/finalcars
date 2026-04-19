<?php

use Illuminate\Support\Facades\DB;
use App\Models\Reward;
use App\Models\ServiceType;

// Clean up existing associations
DB::table('reward_user')->truncate();

// Delete all existing rewards
Reward::query()->delete();

// Recreate rewards for all existing service types
$serviceTypes = ServiceType::all();

foreach ($serviceTypes as $serviceType) {
    $baseName = trim(explode('(', $serviceType->name)[0]);
    $promoName = "Free " . $baseName . " Promo";
    
    Reward::create([
        'name' => $promoName,
        'description' => "Redeem your loyalty points for a free " . strtolower($baseName) . "!",
        'points_cost' => 300,
        'is_active' => true,
    ]);
}

echo "Rewards recreated successfully based on Service Types.\n";
