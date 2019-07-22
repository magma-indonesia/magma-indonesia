<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DeformationData extends Model
{
    protected $connection = 'wovo';

    protected $table = 'dd_tlt';

    protected $primaryKey = 'dd_tlt_id';

    public $timestamps = 'false';

    protected $appends = [
        'unix_time'
    ];

    public function deformation_stations()
    {
        return $this->belongsTo('App\WOVOdat\DeformationStation','ds_id','ds_id');
    }

    public function getUnixTimeAttribute()
    {
        return Carbon::parse($this->attributes['dd_tlt_time'])->timestamp*1000;
    }
}
