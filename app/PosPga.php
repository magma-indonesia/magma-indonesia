<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosPga extends Model
{
    protected $fillable = [

        'code_id',
        'obscode',
        'observatory',
        'address',
        'elevation',
        'latitude',
        'longitude',
        'created_at',
        'updated_at'

    ];
}
