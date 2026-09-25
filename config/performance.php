<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Slow Query Monitoring
    |--------------------------------------------------------------------------
    */

    'slow_query_logging' => (bool) env(
        'TRADIM_SLOW_QUERY_LOGGING',
        false
    ),

    'slow_query_threshold_ms' => (int) env(
        'TRADIM_SLOW_QUERY_THRESHOLD_MS',
        500
    ),

];