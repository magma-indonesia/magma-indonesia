<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class PosPga extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'pos_pgas';

    public function absensi()
    {
        return $this->hasMany('App\v1\Absensi','obscode','obscode');
    }

    public function users()
    {
        return $this->hasManyThrough(
            'App\v1\User',
            'App\v1\Kantor',
            'obscode',
            'vg_nip',
            'obscode',
            'vg_nip'
        )->select('vg_peg.vg_nip', 'vg_peg.vg_nama')
        ->where('vg_peg.status',1);
    }
}
