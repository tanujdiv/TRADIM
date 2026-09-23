<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FFmpeg
    |--------------------------------------------------------------------------
    */

    'ffmpeg' => env(
        'FFMPEG_BINARY',
        'ffmpeg'
    ),

    'ffprobe' => env(
        'FFPROBE_BINARY',
        'ffprobe'
    ),

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    */

    'disk' => env(
        'VIDEO_STORAGE_DISK',
        'public'
    ),

    'source_directory' => 'videos/source',

    'processed_directory' => 'videos/processed',

    'thumbnail_directory' => 'videos/thumbnails',

    /*
    |--------------------------------------------------------------------------
    | Processing
    |--------------------------------------------------------------------------
    */

    'thumbnail_time' => env(
        'VIDEO_THUMBNAIL_TIME',
        1
    ),

    'video_width' => env(
        'VIDEO_PROCESS_WIDTH',
        1280
    ),

    'video_height' => env(
        'VIDEO_PROCESS_HEIGHT',
        720
    ),

];