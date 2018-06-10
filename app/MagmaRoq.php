<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MagmaRoq extends Model
{
    protected $fillable = [
        'noticenumber',
        'utc',
        'magnitude',
        'type',
        'depth',
        'latitude',
        'longitude',
        'area',
        'kota_terdekat',
        'mmi',
        'nearest_volcano'
    ];

    protected $guarded = ['id'];

    protected $hidden = ['id'];

    protected $appends = ['wib','distance'];

    protected function getUtcAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value,'UTC')->toDateTimeString();
    }

    protected function getWibAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['utc'] ,'UTC')->setTimezone('Asia/Jakarta')->toDateTimeString();
    }

    protected function getDistanceAttribute()
    {
        preg_match_all('!\d+!', $this->attributes['area'], $matches);
        return implode(' ', $matches[0]);
    }

    public function tanggapan()
    {
        return $this->hasOne('App\RoqTanggapan','noticenumber_id','noticenumber');
    }
}