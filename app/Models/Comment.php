<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'parent_id',
        'body',
        'comment',
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
    | Video
    |--------------------------------------------------------------------------
    */

    public function video()
    {
        return $this->belongsTo(
            Video::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Parent Comment
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(
            Comment::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Replies
    |--------------------------------------------------------------------------
    */

    public function replies()
    {
        return $this->hasMany(
            Comment::class,
            'parent_id'
        );
    }
}