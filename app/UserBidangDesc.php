<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBidangDesc extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'nama'
    ];

    public function jenis_kegiatan()
    {
        return $this->hasMany('App\MGA\JenisKegiatan','code','code');
    }
}
