<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikHome extends Model
{
    protected $fillable = [
        'date',
        'hit',
    ];

    protected $dates = [
        'date',
    ];
}
