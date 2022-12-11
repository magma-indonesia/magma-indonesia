<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VonaSubscriber extends Model
{
    protected $casts = [
        'status' => 'boolean',
        'real' => 'boolean',
        'exercise' => 'boolean',
        'gladi' => 'boolean',
        'pvmbg' => 'boolean',
        'developer' => 'boolean',
    ];

    protected $guarded = [
        'id',
    ];
}
