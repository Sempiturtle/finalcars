<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$v = Illuminate\Support\Facades\Validator::make(['d' => '2026-05-10'], ['d' => new \App\Rules\AvailableServiceDate]);
if ($v->fails()) {
    echo "FAILED\n";
    print_r($v->errors()->all());
} else {
    echo "PASSED\n";
}
