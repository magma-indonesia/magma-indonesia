<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Ssam extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sd_ssm';

    protected $primaryKey = 'ss_ssm_id';

    public $timestamps = 'false';

    public function rsam_ssam()
    {
        return $this->belongsTo('App\WOVOdat\RsamSsam','sd_sam_id','sd_sam_id');   
    }
}
