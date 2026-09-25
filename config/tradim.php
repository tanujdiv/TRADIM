<?php

return [

    'queues' => [

        'video' => env(
            'TRADIM_VIDEO_QUEUE',
            'videos'
        ),

        'notifications' => env(
            'TRADIM_NOTIFICATION_QUEUE',
            'notifications'
        ),

        'default' => env(
            'TRADIM_DEFAULT_QUEUE',
            'default'
        ),

    ],

];