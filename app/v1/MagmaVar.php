<?php

namespace App\v1;

use App\v1\OldModelVar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
        'var_data_date' => 'date:Y-m-d',
        'sinar_api' => 'boolean'
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

    public function scopeByDate(Builder $query, $noticnumber): Builder
    {
        return $query->from(DB::raw('magma_var, (SELECT magma_var.ga_code, MAX(magma_var.var_noticenumber) AS var_noticenumber FROM magma_var WHERE magma_var.var_noticenumber LIKE "'.$noticnumber.'%" GROUP BY magma_var.ga_code) AS latest_var'))
                ->select('magma_var.*')
                ->whereRaw('magma_var.ga_code = latest_var.ga_code AND magma_var.var_noticenumber = latest_var.var_noticenumber');
    }

    public function scopeLatestVar(Builder $query): Builder
    {
        return $query->from(DB::raw('magma_var, (SELECT magma_var.ga_code, MAX(magma_var.var_noticenumber) AS var_noticenumber FROM magma_var GROUP BY magma_var.ga_code) AS latest_var'))
                ->select('magma_var.*')
                ->whereRaw('magma_var.ga_code = latest_var.ga_code AND magma_var.var_noticenumber = latest_var.var_noticenumber');
    }

}
