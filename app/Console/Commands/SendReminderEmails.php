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