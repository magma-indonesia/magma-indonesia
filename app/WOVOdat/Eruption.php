<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Eruption extends Model
{
    protected $connection = 'wovo';

    protected $table = 'ed';

    protected $primaryKey = 'ed_id';

    public $timestamps = 'false';

    public function volcano()
    {
        return $this->belongsTo('App\WOVOdat\Volcano','vd_id','vd_id');
    }
}
