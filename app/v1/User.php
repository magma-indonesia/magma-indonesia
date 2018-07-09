<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'vg_peg';

    public function kantor()
    {
        return $this->hasOne('App\v1\Kantor','vg_nip','vg_nip');
    }

}
