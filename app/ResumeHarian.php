<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResumeHarian extends Model
{
    protected $fillable = [
        'tanggal',
        'resume',
        'truncated'
    ];

    protected $dates = [
        'tanggal'
    ];
}
