<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Stream extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'channel_name',
        'title',
        'game',
        'tag_ids',
        'views',
        'started_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
    ];
}