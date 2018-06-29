<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Gadd extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'ga_dd';

    public function rekomendasi()
    {
        return $this->hasMany('App\v1\MagmaVar','ga_code','ga_code')
            ->select('ga_code','cu_status','var_data_date','var_rekom')
            ->groupBy('ga_code','cu_status')
            ->orderBy('var_data_date','desc');
    }
}
