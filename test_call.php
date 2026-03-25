<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$to = '+639296645607';
$message = 'This is a test maintenance reminder from your Car System. Please schedule your service.';

echo "Initiating test call to: $to\n";

$notification = new App\Notifications\OverdueFollowUpCall($message);
$sid = $notification->triggerCall($to);

echo "Result SID: $sid\n";
if ($sid !== 'FAILED' && $sid !== 'SIMULATED_SID') {
    echo "Check your phone, the call should be coming through!\n";
} else {
    echo "Call failed or was simulated. Check your .env file or logs.\n";
}
