<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'vg_nip';

    protected $table = 'pga_pos';

    protected $fillable = [
        'vg_nip',
        'ga_code',
        'obscode'
    ];

    public function user()
    {
        return $this->belongsTo('App\v1\User','vg_nip','vg_nip');
    }

    public function absensi()
    {
        return $this->hasMany('App\v1\Absensi','vg_nip','vg_nip');
    }

    public function penempatan()
    {
        return $this->belongsTo('App\v1\PosPga','obscode','obscode');
    }
}
