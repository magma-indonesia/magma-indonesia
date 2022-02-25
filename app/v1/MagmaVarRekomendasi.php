<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVarRekomendasi extends Model
{
    protected $connection = 'magma';

    protected $fillable = [
        'ga_code',
        'status',
        'date',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd', 'ga_code', 'ga_code');
    }

    public function lists()
    {
        return $this->hasMany('App\v1\MagmaVarListRekomendasi');
    }

}
