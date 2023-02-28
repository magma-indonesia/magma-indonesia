<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MagmaVarOptimize extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    public function user()
    {
        return $this->belongsTo('App\v1\User', 'var_nip_pelapor', 'vg_nip');
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd', 'ga_code', 'ga_code');
    }

    public function getVarLogLocalAttribute()
    {
        $zone = $this->gunungapi->ga_zonearea;

        switch ($zone) {
            case 'WIB':
                $zone = 'Asia/Jakarta';
                break;
            case 'WITA':
                $zone = 'Asia/Makassar';
                break;
            default:
                $zone = 'Asia/Jayapura';
                break;
        }

        return Carbon::createFromTimeString($this->attributes['var_log'], $zone)->format('Y-m-d H:i:s');
    }

    public function getLevelAttribute()
    {
        switch ($this->attributes['cu_status']) {
            case 'Level I (Normal)':
                return 1;
            case 'Level II (Waspada)':
                return 2;
            case 'Level III (Siaga)':
                return 3;
            case 'Level IV (Siaga)':
                return 4;
        }
    }
}
