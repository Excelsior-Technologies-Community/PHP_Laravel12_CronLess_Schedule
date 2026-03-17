# PHP_Laravel12_CronLess_Schedule

## Introduction

PHP_Laravel12_CronLess_Schedule is a Laravel 12 project that demonstrates how to automate task scheduling without relying on traditional server cron jobs.

The project focuses on Laravel’s modern scheduling system using the `routes/console.php` file and showcases how to create and run custom Artisan commands automatically.

It also explores the concept of cronless scheduling using the Spatie Laravel Cronless Schedule package and highlights real-world limitations when working in different environments such as Windows.

---

## Project Overview

This project demonstrates how to:

- Create custom Artisan commands in Laravel 12
- Schedule tasks using the new Laravel structure (without Kernel.php)
- Execute scheduled tasks automatically using `php artisan schedule:work`
- Log task execution output into custom log files
- Understand cronless scheduling concepts and their limitations

The project is designed to be beginner-friendly while also reflecting real-world development practices used in Laravel applications.

---

## Project Setup

## Step 1: Install Laravel 12

```bash
composer create-project laravel/laravel PHP_Laravel12_CronLess_Schedule "12.*"
cd PHP_Laravel12_CronLess_Schedule
```
---

## Step 2: Install Spatie Cronless Scheduler

Install the package as a development dependency:

```bash
composer require spatie/laravel-cronless-schedule --dev
```
---

## Step 3: Define and Schedule Commands

Open:

routes/console.php

Define a default command and schedule a custom command:

```php
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
```

---

## Step 4: Create Custom Command

```bash
php artisan make:command SendReminderEmails
```

Update file:

app/Console/Commands/SendReminderEmails.php

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     */
    protected $description = 'Send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Log message
        \Log::info('Reminder emails sent at ' . now());

        // Output in terminal
        $this->info('Reminder emails sent successfully!');
    }
}
```

---

## Step 5: Run Scheduler 

```bash
php artisan schedule:work
```

- Runs continuously
- Executes tasks every minute

Log files:

storage/logs/laravel.log  
storage/logs/emails.log

---

## Step 6: Logging & Testing

- Logs for scheduled tasks are stored in storage/logs/laravel.log.

- Open another terminal to test the command manually:

```bash
php artisan emails:send
```

---

## Output

<img src="screenshots/Screenshot 2026-03-17 103035.png" width="1000">

<img src="screenshots/Screenshot 2026-03-17 101602.png" width="1000">

<img src="screenshots/Screenshot 2026-03-17 111240.png" width="1000">

<img src="screenshots/Screenshot 2026-03-17 111222.png" width="1000">

---

## Project Structure

```
PHP_Laravel12_CronLess_Schedule/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SendReminderEmails.php
├── bootstrap/
│   └── app.php
├── routes/
│   └── console.php
├── storage/
│   └── logs/
├── artisan
├── composer.json
└── README.md
```

---

## Cronless Scheduler Note

This project includes the Spatie Laravel Cronless Schedule package to demonstrate cronless scheduling.

However, the command:

```bash
php artisan schedule:run-cronless
```
may not work properly on Windows environments due to limitations in ReactPHP, which requires non-blocking stream support.

Because of this, the project uses:

```bash
php artisan schedule:work
```
as a reliable and cross-platform solution.

The cronless scheduler works correctly on:
- Linux
- macOS
- Windows (via WSL)

---

Your PHP_Laravel12_CronLess_Schedule Project is now ready!
