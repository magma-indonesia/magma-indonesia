<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicCheckLocation extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'ip_address',
    ];
}
