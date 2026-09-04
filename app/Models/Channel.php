<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'handle',
        'description',
        'avatar',
        'banner',
        'subscriber_count',
        'video_count',
        'total_views',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'subscriber_count' => 'integer',
            'video_count' => 'integer',
            'total_views' => 'integer',
            'is_verified' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

    public function videos(): HasMany
    {
        return $this->hasMany(
            Video::class,
            'channel_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Subscriptions
    |--------------------------------------------------------------------------
    */

    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            Subscription::class,
            'channel_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Avatar URL
    |--------------------------------------------------------------------------
    */

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        return asset(
            'storage/' . $this->avatar
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Banner URL
    |--------------------------------------------------------------------------
    */

    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner) {
            return null;
        }

        return asset(
            'storage/' . $this->banner
        );
    }
}