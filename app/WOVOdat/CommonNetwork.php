<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class CommonNetwork extends Model
{
    protected $connection = 'wovo';

    protected $table = 'cn';

    protected $primaryKey = 'cn_id';

    public $timestamps = 'false';

    public function volcano()
    {
        return $this->belongsTo('App\WOVOdat\Volcano','vd_id','vd_id');
    }
}
