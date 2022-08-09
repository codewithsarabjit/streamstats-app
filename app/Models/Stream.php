<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stream extends Authenticatable
{
    protected $fillable = [
        'channel_name',
        'title',
        'game',
        'views',
        'started_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];
}