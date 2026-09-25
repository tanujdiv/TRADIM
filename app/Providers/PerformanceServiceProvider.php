<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class PerformanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!config('performance.slow_query_logging')) {
            return;
        }

        $threshold = max(
            1,
            (int) config(
                'performance.slow_query_threshold_ms',
                500
            )
        );

        DB::listen(
            function (QueryExecuted $query) use ($threshold): void {

                if ($query->time < $threshold) {
                    return;
                }

                Log::warning(
                    'Tradim slow database query',
                    [
                        'connection' => $query->connectionName,
                        'duration_ms' => $query->time,
                        'sql' => $query->sql,
                    ]
                );
            }
        );
    }
}