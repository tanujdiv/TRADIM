<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

    public function videos()
    {
        return $this->hasMany(
            Video::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Subscriptions
    |--------------------------------------------------------------------------
    */

    public function subscriptions()
    {
        return $this->hasMany(
            Subscription::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Likes
    |--------------------------------------------------------------------------
    */

    public function likes()
    {
        return $this->hasMany(
            Like::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Avatar URL
    |--------------------------------------------------------------------------
    */

    public function getAvatarUrlAttribute()
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

    public function getBannerUrlAttribute()
    {
        if (!$this->banner) {
            return null;
        }

        return asset(
            'storage/' . $this->banner
        );
    }
}