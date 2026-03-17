<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Command definition
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule custom command
Schedule::command('emails:send')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/emails.log'));