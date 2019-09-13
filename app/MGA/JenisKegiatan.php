<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    protected $fillable = [
        'nama','code'
    ];

    public function kegiatan()
    {
        return $this->hasMany('App\MGA\Kegiatan');
    }

    public function bidang()
    {
        return $this->belongsTo('App\UserBidang','code','code');
    }

    public function detail_kegiatan()
    {
        return $this->hasManyThrough('App\MGA\DetailKegiatan','App\MGA\Kegiatan');
    }
}
