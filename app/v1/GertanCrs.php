<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Indonesia;

class GertanCrs extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'idx';

    protected $dates = ['crs_log','crs_dtm'];

    protected $table = 'magma_crs';

    protected $appends = [
        'date'
    ];

    protected $fillable = [
        'crs_usr',
        'crs_pho',
        'crs_ids',
        'crs_dtm',
        'crs_zon',
        'crs_typ',
        'crs_prv',
        'crs_cty',
        'crs_rgn',
        'crs_vil',
        'crs_bwd',
        'crs_lat',
        'crs_lon',
        'crs_brd',
        'crs_fsr',
        'crs_tsc',
        'crs_ksc',
        'crs_ksc',
        'crs_ftp',
        'crs_val',
        'crs_vor',
        'crs_sta',
        'crs_soa',
        'crs_dvc',
        'crs_log',
        'lat_usr',
        'long_usr'
    ];

    public function getDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s' ,$this->attributes['crs_log'])->format('Y-m-d');
    }
    
    public function getCrsPrvAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsCtyAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsRgnAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsVilAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsBwdAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsFtpAttribute($value)
    {
        return empty($value) ? null : $value;
    }

    public function tanggapan()
    {
        return $this->hasOne('App\v1\MagmaSigertan','crs_ids','crs_ids');
    }
}
