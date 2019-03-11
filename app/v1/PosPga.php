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
}
