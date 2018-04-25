<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndonesiaCity extends Model
{
    protected $table = 'indonesia_cities';

    public $timestamps = false;

    public function province()
	{
	    return $this->belongsTo('App\IndonesiaProvince', 'province_id');
	}

	public function districts()
    {
        return $this->hasMany('App\IndonesiaDistrict', 'city_id');
    }

    public function villages()
    {
        return $this->hasManyThrough('App\IndonesiaVillage', 'App\IndonesiaDistrict');
    }

    public function getNameAttribute()
    {
        return title_case($this->attributes['name']);
    }

    public function getProvinceNameAttribute()
    {
        return title_case($this->province->name);
    }
}
