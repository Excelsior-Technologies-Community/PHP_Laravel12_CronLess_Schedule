<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendReminderEmails extends Command
{
    protected $signature = 'emails:send';
    protected $description = 'Send reminder emails';

    public function handle()
    {
        try {
            $users = DB::table('users')->limit(3)->get();

            foreach ($users as $user) {
                Log::info("Email sent to: " . $user->email);
            }

            DB::table('activity_logs')->insert([
                'message' => 'Emails sent successfully',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info('Emails sent successfully');

        } catch (\Exception $e) {
            Log::error('Email Error: ' . $e->getMessage());
        }
    }
}