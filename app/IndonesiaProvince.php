<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndonesiaProvince extends Model
{
    protected $table = 'indonesia_provinces';

    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany('App\IndonesiaCity', 'province_id');
    }

    public function districts()
    {
        return $this->hasManyThrough('App\IndonesiaDistrict', 'App\IndonesiaCity');
    }

    public function getNameAttribute()
    {
        return title_case($this->attributes['name']);
    }
}
