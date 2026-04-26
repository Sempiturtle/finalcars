<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Rules\AvailableServiceDate;
use Illuminate\Support\Facades\Validator;

// Sunday, May 10, 2026
$data = [
    'services' => [
        ['date' => '2026-05-10']
    ]
];

$rules = [
    'services.*.date' => ['nullable', 'date', new AvailableServiceDate]
];

$v = Validator::make($data, $rules);

echo "Testing Array Validation for Sunday (May 10):\n";
if ($v->fails()) {
    echo "FAILED (Correct behavior)\n";
    print_r($v->errors()->all());
} else {
    echo "PASSED (BUG DETECTED!)\n";
}
