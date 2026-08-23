<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoView extends Model
{
    use HasFactory;


    public $timestamps = false;


    protected $fillable = [

        'video_id',
        'user_id',
        'session_id',
        'ip_hash',
        'watched_seconds',
        'created_at',

    ];


    protected function casts(): array
    {
        return [

            'created_at' => 'datetime',

        ];
    }


    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}