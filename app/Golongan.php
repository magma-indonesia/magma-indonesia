<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pangkat',
        'golongan',
        'ruang',
    ];

    protected $guarded  = [
        'id'
    ];
}
