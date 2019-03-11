<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id_abs';

    protected $table = 'pga_abs';

    protected $fillable = [
        'vg_nip',
        'obscode',
        'ga_code',
        'date_abs',
        'checkin_time',
        'checkin_image',
        'checkin_lat',
        'checkin_lon',
        'checkin_dist',
        'checkout_time',
        'checkout_image',
        'checkout_lat',
        'checkout_lon',
        'checkout_dist',
        'length_work',
        'nip_ver',
        'ket_abs',
    ];

    protected $guard = ['id_abs'];

    public function user()
    {
        return $this->belongsTo('App\v1\User','vg_nip','vg_nip');
    }

    public function pos()
    {
        return $this->belongsTo('App\v1\PosPga','obscode','obscode');
    }
}
