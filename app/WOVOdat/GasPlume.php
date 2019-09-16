<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class GasPlume extends Model
{
    protected $connection = 'wovo';

    protected $table = 'gd_plu';

    protected $primaryKey = 'gd_plu_id';

    public $timestamps = 'false';

    protected $appends = [
        'unix_time'
    ];

    public function gas_stations()
    {
        return $this->belongsTo('App\WOVOdat\GasStation','gs_id','gs_id');
    }

    public function getUnixTimeAttribute()
    {
        return Carbon::parse($this->attributes['gd_plu_time'])->timestamp*1000;
    }
}
