<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fungsional extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama'
    ];

    protected $guarded  = [
        'id'
    ];
}
