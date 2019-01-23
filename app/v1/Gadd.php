<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Gadd extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'ga_dd';

    public function normal()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level I (Normal)')
                    ->orderBy('var_data_date','desc');
    }

    public function waspada()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level II (Waspada)')
                    ->orderBy('var_data_date','desc');
    }

    public function siaga()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level III (Siaga)')
                    ->orderBy('var_data_date','desc');
    }

    public function awas()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level IV (Awas)')
                    ->orderBy('var_data_date','desc');
    }

    public function var()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->orderBy('var_log','desc');
    }
}
