<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikLaporanHarian extends Model
{
    protected $fillable = [
        'date',
        'hit',
    ];

    protected $dates = [
        'date:Y-m-d',
    ];
}
