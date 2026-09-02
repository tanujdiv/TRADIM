<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actor_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Notification Owner
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
    | Actor
    |--------------------------------------------------------------------------
    */

    public function actor()
    {
        return $this->belongsTo(
            User::class,
            'actor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Read Status
    |--------------------------------------------------------------------------
    */

    public function isRead(): bool
    {
        return !is_null(
            $this->read_at
        );
    }
}