<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $fillable = [
        'ip_address',
        'name',
        'hit',
    ];
}
