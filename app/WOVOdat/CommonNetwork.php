<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class CommonNetwork extends Model
{
    protected $connection = 'wovo';

    protected $table = 'cn';

    protected $primaryKey = 'cn_id';

    public $timestamps = 'false';

    public function volcano()
    {
        return $this->belongsTo('App\WOVOdat\Volcano','vd_id','vd_id');
    }

    public function deformation_stations()
    {
        return $this->hasMany('App\WOVOdat\DeformationStation','cn_id','cn_id');
    }

    public function fields_stations()
    {
        return $this->hasMany('App\WOVOdat\FieldsStation','cn_id','cn_id');
    }

    public function gas_stations()
    {
        return $this->hasMany('App\WOVOdat\GasStation','cn_id','cn_id');
    }

    public function hydrologic_stations()
    {
        return $this->hasMany('App\WOVOdat\HydrologicStation','cn_id','cn_id');
    }

    public function meteo_stations()
    {
        return $this->hasMany('App\WOVOdat\MeteoStation','cn_id','cn_id');
    }

    public function thermal_stations()
    {
        return $this->hasMany('App\WOVOdat\ThermalStation','cn_id','cn_id');
    }
}
