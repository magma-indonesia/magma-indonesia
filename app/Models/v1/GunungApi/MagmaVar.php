<?php

namespace App\Models\v1\GunungApi;

use App\Models\v1\MagmaModel;
use App\Traits\IgnoreMutators;

class MagmaVar extends MagmaModel
{

    use IgnoreMutators;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'magma_var';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'no',
    ];
}
