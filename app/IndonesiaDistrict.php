<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndonesiaDistrict extends Model
{
	protected $table = 'indonesia_districts';

    public $timestamps = false;

    public function city()
	{
	    return $this->belongsTo('App\IndonesiaCity', 'city_id');
	}

	public function villages()
    {
        return $this->hasMany('App\IndonesiaVillage', 'district_id');
    }

    public function getNameAttribute()
    {
        return title_case($this->attributes['name']);
    }

    public function getCityNameAttribute()
    {
        return title_case($this->city->name);
    }

    public function getProvinceNameAttribute()
    {
        return title_case($this->city->province->name);
    }
}
