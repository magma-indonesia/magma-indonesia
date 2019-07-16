<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Rsam extends Model
{
    protected $connection = 'wovo';

    protected $table = 'ss';

    protected $primaryKey = 'ss_rsm_id';

    public $timestamps = 'false';

    public function rsam()
    {
        return $this->belongsTo('App\WOVOdat\Rsam','sd_sam_id','sd_sam_id');   
    }
}
