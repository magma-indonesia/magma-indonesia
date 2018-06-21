<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $fillable = [
        'code',
        'nama',
        'tzone',
        'address',
        'elevation',
        'latitude',
        'longitude'
    ];
}
