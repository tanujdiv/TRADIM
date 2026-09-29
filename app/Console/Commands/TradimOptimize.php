<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TradimOptimize extends Command
{
    protected $signature = 'tradim:optimize';

    protected $description = 'Optimize Tradim application for production performance';

    public function handle(): int
    {
        $this->info('Starting Tradim performance optimization...');

        $this->newLine();

        $this->info('Clearing old caches...');

        Artisan::call('optimize:clear');

        $this->line(
            Artisan::output()
        );

        $this->newLine();

        $this->info('Caching configuration...');

        Artisan::call('config:cache');

        $this->line(
            Artisan::output()
        );

        $this->newLine();

        $this->info('Caching routes...');

        Artisan::call('route:cache');

        $this->line(
            Artisan::output()
        );

        $this->newLine();

        $this->info('Caching views...');

        Artisan::call('view:cache');

        $this->line(
            Artisan::output()
        );

        $this->newLine();

        $this->info('Tradim performance optimization completed.');

        $this->newLine();

        $this->table(
            ['Optimization', 'Status'],
            [
                ['Configuration cache', 'Enabled'],
                ['Route cache', 'Enabled'],
                ['View cache', 'Enabled'],
                ['Application cache cleanup', 'Completed'],
            ]
        );

        return self::SUCCESS;
    }
}