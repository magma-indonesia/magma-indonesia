<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVarListRekomendasi extends Model
{
    protected $connection = 'magma';

    protected $fillable = [
        'rekomendasi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
