<?php

namespace App\v1;

use App\Traits\v1\FilterMagmaVar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MagmaVarOptimize extends Model
{
    use FilterMagmaVar;

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

    /**
     * Get local time
     *
     * @return void
     */
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

        return Carbon::createFromTimeString($this->attributes['var_log'], 'Asia/Jakarta')
            ->setTimezone($zone);
    }

    /**
     * Get Level as integer
     *
     * @return void
     */
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

    /**
     * Get previous Level as integer
     *
     * @return void
     */
    public function getPreviousLevelAttribute()
    {
        switch ($this->attributes['pre_status']) {
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

    public function getActivityChangeAttribute()
    {
        if ($this->previous_level < $this->level)
            return 'increase';

        if ($this->previous_level > $this->level)
            return 'decrease';

        return 'no change';
    }
}
