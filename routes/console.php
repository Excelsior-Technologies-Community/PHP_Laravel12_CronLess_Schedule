<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use App\Models\ScheduleSetting;
use App\Models\TaskExecutionLog;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('emails:send', function () {
    $startMemory = memory_get_usage();
    $this->info('Reminder emails processed successfully.');
    $endMemory = memory_get_usage();
    
    TaskExecutionLog::create([
        'command_signature' => 'emails:send',
        'status' => 'Success',
        'memory_used' => round(($endMemory - $startMemory) / 1024 / 1024, 2),
        'output' => 'Reminder emails processed successfully.',
        'executed_at' => now()
    ]);
});

Artisan::command('logs:clean', function () {
    $startMemory = memory_get_usage();
    $this->info('System log buffer flushed.');
    $endMemory = memory_get_usage();

    TaskExecutionLog::create([
        'command_signature' => 'logs:clean',
        'status' => 'Success',
        'memory_used' => round(($endMemory - $startMemory) / 1024 / 1024, 2),
        'output' => 'System log buffer flushed.',
        'executed_at' => now()
    ]);
});

Artisan::command('system:health', function () {
    $startMemory = memory_get_usage();
    $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
    $this->info("Current core allocation: {$memoryUsage} MB");
    $endMemory = memory_get_usage();

    TaskExecutionLog::create([
        'command_signature' => 'system:health',
        'status' => 'Success',
        'memory_used' => $memoryUsage,
        'output' => "Current core allocation: {$memoryUsage} MB",
        'executed_at' => now()
    ]);
});

if (Schema::hasTable('schedule_settings')) {
    $registeredTasks = ['emails:send', 'logs:clean', 'system:health'];
    foreach ($registeredTasks as $task) {
        $setting = ScheduleSetting::firstOrCreate(
            ['command_signature' => $task],
            ['interval_type' => 'everyMinute', 'is_active' => true]
        );

        if ($setting->is_active) {
            $type = $setting->interval_type;
            Schedule::command($task)->$type()->appendOutputTo(storage_path("logs/scheduled_" . Str::slug($task) . ".log"));
        }
    }
}