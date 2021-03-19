<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $fillable = [
        'code',
        'nama',
        'tzone',
        'address',
        'elevation',
        'latitude',
        'longitude'
    ];

    public function pos_pga()
    {
        return $this->belongsTo('App\PosPga','code','obscode');
    }

    public function administrasi()
    {
        return $this->belongsTo('App\UserAdministratif', 'code', 'kantor_id');
    }

}
