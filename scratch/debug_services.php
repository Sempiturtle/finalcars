<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vehicle;

foreach(Vehicle::all() as $v) {
    echo "Plate: " . $v->plate_number . "\n";
    echo "Services JSON: " . json_encode($v->services, JSON_PRETTY_PRINT) . "\n";
    echo "Service Logs Table Count: " . $v->serviceLogs()->count() . "\n";
    echo "--------------------------\n";
}
