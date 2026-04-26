<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vehicle;
use App\Models\ServiceType;
use Carbon\Carbon;

$vehicle = Vehicle::where('plate_number', 'rewe')->first();
echo "Initial services count: " . count($vehicle->services ?? []) . "\n";

// Simulation: Admin opens Edit page (sees 6 services)
$adminSeenServices = $vehicle->services;

// Simulation: User logs a service (7th service)
$userServices = $vehicle->services;
$userServices[] = [
    'type' => 'Oil Change',
    'mode' => 'Walk-in',
    'cost' => 3201,
    'status' => 'scheduled',
    'date' => Carbon::now()->toDateString(),
    'notes' => 'User added this'
];
$vehicle->update(['services' => $userServices]);
echo "User added 7th service. DB count: " . count($vehicle->fresh()->services) . "\n";

// Simulation: Admin saves their page (overwriting with the 6 they saw)
echo "Admin saving their stale data (6 services)...\n";
$vehicle->update(['services' => $adminSeenServices]);

echo "Final DB count: " . count($vehicle->fresh()->services) . "\n";
echo "Did we lose the user's service? " . (count($vehicle->fresh()->services) < 7 ? "YES" : "NO") . "\n";
