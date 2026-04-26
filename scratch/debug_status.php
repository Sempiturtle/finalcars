<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

$v = Vehicle::first();

// Step 1: Reset to 'overdue' to simulate the stale state
DB::table('vehicles')->where('id', $v->id)->update(['status' => 'overdue']);
$v->refresh();
echo "=== RESET STATE ===\n";
echo "DB status: '{$v->getRawOriginal('status')}'\n";
echo "Calculated: '{$v->calculated_status}'\n\n";

// Step 2: Simulate dashboard corrective pass
echo "=== DASHBOARD CORRECTIVE PASS ===\n";
$calc = $v->calculated_status;
if ($v->getRawOriginal('status') !== $calc) {
    DB::table('vehicles')->where('id', $v->id)->update(['status' => $calc]);
    echo "FIXED: '{$v->getRawOriginal('status')}' -> '{$calc}'\n";
} else {
    echo "No fix needed.\n";
}

$v->refresh();
echo "\nFinal DB status: '{$v->getRawOriginal('status')}'\n";
echo "Final calculated: '{$v->calculated_status}'\n";
echo "Match: " . ($v->getRawOriginal('status') === $v->calculated_status ? 'YES' : 'NO') . "\n";
