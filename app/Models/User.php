<?php

namespace App\Models;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Channel
    |--------------------------------------------------------------------------
    */

    public function channel(): HasOne
    {
        return $this->hasOne(Channel::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    */

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Likes
    |--------------------------------------------------------------------------
    */

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
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
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    public function notifications(): HasMany
    {
        return $this->hasMany(
            Notification::class,
            'user_id'
        );
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(
            Notification::class,
            'user_id'
        )->where('is_read', false);
    }
}