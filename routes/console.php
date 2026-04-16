<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
Schedule::command('maintenance:send-reminders')->dailyAt('08:00');
Schedule::command('app:check-vehicle-overdue')->dailyAt('09:00');
