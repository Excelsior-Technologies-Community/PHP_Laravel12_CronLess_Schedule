<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Artisan Demo Command
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Show inspirational quote');


/*
|--------------------------------------------------------------------------
| SCHEDULED TASKS
|--------------------------------------------------------------------------
*/

// 1. Send reminder emails every minute
Schedule::command('emails:send')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/emails.log'));

// 2. Clean logs daily
Schedule::command('logs:clean')
    ->daily()
    ->appendOutputTo(storage_path('logs/cleanup.log'));

// 3. System health check every 5 minutes
Schedule::command('system:health')
    ->everyFiveMinutes()
    ->appendOutputTo(storage_path('logs/health.log'));