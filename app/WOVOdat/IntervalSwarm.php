<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IntervalSwarm extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sd_ivl';

    protected $primaryKey = 'sd_ivl_id';

    public $timestamps = 'false';

    protected $appends = [
        'eq_name',
        'eq_magma',
        'unix_time',
    ];

    public function network()
    {
        return $this->belongsTo('App\WOVOdat\SeismicNetwork','sn_id','sn_id');
    }

    public function station()
    {
        return $this->belongsTo('App\WOVOdat\StationSeismic','ss_id','ss_id');
    }

    public function getUnixTimeAttribute()
    {
        $date = Carbon::parse($this->attributes['sd_ivl_stime'])->format('Y-m-d');
        return Carbon::parse($date)->timestamp*1000;
    }

    public function getEqNameAttribute()
    {
        switch ($this->attributes['sd_ivl_eqtype']) {
            case 'LF':
                return 'Low Frequency';
            case 'R_D':
                return 'Tektonik Jauh';
            case 'R_L':
                return 'Tektonik Lokal';
            case 'VT_D':
                return 'Vulkanik Dalam';
            case 'VT_S':
                return 'Vulkanik Dangkal';
            case 'LF_T':
                return 'Tornillo';
            case 'H':
                return 'Hybrid';
            case 'T_G':
                return 'Tremor Menerus';
            case 'T_H':
                return 'Tremor Harmonik';
            case 'PF':
                return 'Awan Panas Guguran';
            case 'MP':
                return 'Multiphase';
            case 'G':
                return 'Hembusan';
            case 'RF':
                return 'Guguran';
            case 'E':
                return 'Erupsi';
            case 'U':
                return 'Sumber gempa tidak diketahui';
            case 'O':
                return 'Sumber gempa bukan dari Vulkanik';
            default:
                return 'Undefined';
        }
    }

    public function getEqMagmaAttribute()
    {
        switch ($this->attributes['sd_ivl_eqtype']) {
            case 'LF':
                return 'lof';
            case 'R_D':
                return 'tej';
            case 'R_L':
                return 'tel';
            case 'VT_D':
                return 'vta';
            case 'VT_S':
                return 'vtb';
            case 'LF_T':
                return 'tor';
            case 'H':
                return 'hyb';
            case 'T_G':
                return 'mtr';
            case 'T_H':
                return 'hrm';
            case 'PF':
                return 'apg';
            case 'MP':
                return 'hyb';
            case 'G':
                return 'hbs';
            case 'RF':
                return 'gug';
            case 'E':
                return 'lts';
            case 'U':
                return 'xxx';
            case 'O':
                return 'xxx';
            default:
                return 'xxx';
        }
    }
}
