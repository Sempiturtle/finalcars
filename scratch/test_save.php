<?php

require dirname(__DIR__).'/vendor/autoload.php';
$app = require_once dirname(__DIR__).'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Vehicle;
use App\Models\ServiceLog;

$v = Vehicle::first();
$countBefore = ServiceLog::count();
echo "Count before: $countBefore\n";

$services = $v->services ?? [];
$services[] = [
    'type' => 'Diagnostic', 
    'mode' => 'Walk-in', 
    'cost' => 500, 
    'status' => 'scheduled', 
    'date' => '2026-04-28'
];

$v->update(['services' => $services]);
$v->refresh();

$countAfter = ServiceLog::count();
echo "Count after: $countAfter\n";

if ($countAfter > $countBefore) {
    echo "Success! Service log created.\n";
} else {
    echo "Failure! No service log created.\n";
}
