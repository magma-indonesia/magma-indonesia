<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VonaSubscriber extends Model
{
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'real',
        'exercise',
        'gladi',
        'pvmbg',
        'status',
    ];
}
