<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramNotification extends Model
{
    protected $dates = [
        'datetime',
    ];

    protected $fillable = [
        'model',
        'model_id',
        'datetime',
    ];
}
