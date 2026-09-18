<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoView extends Model
{
    use HasFactory;

    protected $table = 'video_views';

    public $timestamps = false;

    protected $fillable = [
        'video_id',
        'user_id',
        'session_id',
        'ip_hash',
        'last_viewed_at',
        'last_position',
        'watched_seconds',
        'completed',
    ];

    protected $casts = [
        'last_viewed_at' => 'datetime',
        'last_position' => 'integer',
        'watched_seconds' => 'integer',
        'completed' => 'boolean',
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(
            Video::class,
            'video_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}