<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseMonitorCommand extends Command
{
    protected $signature = 'db:monitor';
    protected $description = 'Check if the database connection is available';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
            $this->info('Database connection is available');
            return 0;
        } catch (Exception $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
