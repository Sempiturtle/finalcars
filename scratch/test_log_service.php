<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vehicle;
use App\Models\ServiceType;
use Carbon\Carbon;

$vehicle = Vehicle::where('plate_number', 'rewe')->first();
$serviceType = ServiceType::where('name', 'Oil Change')->first();

echo "Initial services count: " . count($vehicle->services ?? []) . "\n";

$services = $vehicle->services ?? [];
$services[] = [
    'type' => $serviceType->name,
    'mode' => 'Walk-in',
    'cost' => $serviceType->base_cost,
    'status' => 'scheduled',
    'date' => Carbon::now()->toDateString(),
    'notes' => 'Testing manual log',
];

echo "Attempting to update vehicle...\n";
$updated = $vehicle->update([
    'services' => $services,
    'next_service_date' => Carbon::now()->addMonths(6)->toDateString(),
    'status' => 'scheduled'
]);

echo "Update result: " . ($updated ? "Success" : "Failed") . "\n";

$vehicle->refresh();
echo "Post-update services count: " . count($vehicle->services ?? []) . "\n";
echo "Services JSON: " . json_encode($vehicle->services, JSON_PRETTY_PRINT) . "\n";
echo "Next Service Date: " . $vehicle->next_service_date->toDateString() . "\n";
