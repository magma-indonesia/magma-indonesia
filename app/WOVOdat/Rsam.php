<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rsam extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sd_rsm';

    protected $primaryKey = 'sd_rsm_id';

    public $timestamps = 'false';

    protected $appends = [
        'unix_time'
    ];

    public function rsam_ssam()
    {
        return $this->belongsTo('App\WOVOdat\RsamSsam','sd_sam_id','sd_sam_id');   
    }

    public function getUnixTimeAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s' ,$this->attributes['sd_rsm_stime'])->timestamp;
    }
}
