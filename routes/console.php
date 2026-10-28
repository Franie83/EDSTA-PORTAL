<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send tourism permit expiry reminders daily at 8:00 AM Nigeria time.
Schedule::command('permits:send-expiry-reminders')->dailyAt('08:00');
