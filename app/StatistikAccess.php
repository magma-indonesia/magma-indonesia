<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikAccess extends Model
{
    protected $fillable = [
        'ip_address',
        'hit',
        'ips',
    ];
}
