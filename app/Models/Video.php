<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;


    protected $fillable = [

        'user_id',
        'channel_id',
        'category_id',
        'title',
        'slug',
        'description',
        'video_path',
        'thumbnail_path',
        'duration',
        'visibility',
        'status',
        'views_count',
        'likes_count',
        'dislikes_count',
        'comments_count',
        'published_at',

    ];


    protected function casts(): array
    {
        return [

            'published_at' => 'datetime',

        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function views(): HasMany
    {
        return $this->hasMany(VideoView::class);
    }


    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}