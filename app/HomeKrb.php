<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeKrb extends Model
{
    protected $dates = [
        'expired_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'expired_at' 
    ];
}
