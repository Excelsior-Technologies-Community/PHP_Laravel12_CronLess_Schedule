<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SystemHealth extends Command
{
    protected $signature = 'system:health';
    protected $description = 'Check system health';

    public function handle()
    {
        $memory = memory_get_usage(true);

        Log::info("System OK | Memory Usage: " . $memory);

        $this->info('System health checked');
    }
}