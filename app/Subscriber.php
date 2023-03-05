<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'verified_at',
        'expired_at',
    ];
}
