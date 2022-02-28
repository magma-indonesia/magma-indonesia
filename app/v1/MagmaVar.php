<?php

namespace App\v1;

use App\v1\OldModelVar;

class MagmaVar extends OldModelVar
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    protected $guarded = ['no'];

    protected $appends = [
        'data_date',
    ];

    protected $casts = [
        'var_log' => 'datetime:Y-m-d H:i:s',
        'var_data_date' => 'date:Y-m-d'
    ];

    public function getVarKetlainAttribute($value)
    {
        return (empty($value) || strlen($value) < 7) ? null : $value;
    }

    public function getDataDateAttribute($value)
    {
        return $this->attributes['var_data_date'];
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }

    public function compile()
    {
        return $this->hasOne('App\v1\CompileVar','ga_code','ga_code');
    }

    public function rekomendasi()
    {
        return $this->belongsTo('App\v1\MagmaVarRekomendasi', 'magma_var_rekomendasi_id','id');
    }

    // protected $dates = ['var_data_date'];
}
