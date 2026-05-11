<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanLogs extends Command
{
    protected $signature = 'logs:clean';
    protected $description = 'Clean laravel log file';

    public function handle()
    {
        $file = storage_path('logs/laravel.log');

        if (File::exists($file)) {
            File::put($file, '');
            $this->info('Logs cleaned successfully');
        } else {
            $this->warn('Log file not found');
        }
    }
}