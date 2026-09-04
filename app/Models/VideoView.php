<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    protected $table = 'video_views';

    public const UPDATED_AT = null;

    protected $fillable = [
        'video_id',
        'user_id',
        'session_id',
        'ip_hash',
        'last_position',
        'watched_seconds',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'last_position' => 'integer',
        'watched_seconds' => 'integer',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}