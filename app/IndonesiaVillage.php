<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndonesiaVillage extends Model
{
	protected $table = 'indonesia_villages';

    public $timestamps = false;

	public function district()
	{
	    return $this->belongsTo('App\IndonesiaDistrict', 'district_id');
	}

	public function getNameAttribute()
    {
        return title_case($this->attributes['name']);
    }

	public function getDistrictNameAttribute()
    {
        return title_case($this->district->name);
    }

	public function getCityNameAttribute()
    {
        return title_case($this->district->city->name);
    }

	public function getProvinceNameAttribute()
    {
        return title_case($this->district->city->province->name);
    }
}
