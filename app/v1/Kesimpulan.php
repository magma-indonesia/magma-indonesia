<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Kesimpulan extends Model
{
    protected $connection = 'magma';

    protected $table = 'kesimpulan';

    protected $fillable = [
        'ga_code',
        'level',
        'kesimpulan_1',
        'kesimpulan_2',
        'kesimpulan_3',
        'kesimpulan_4',
        'kesimpulan_5',
        'nip'
    ];

    protected $appends = [
        'status',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }

    public function vars()
    {
        return $this->hasMany('App\v1\MagmaVar','kesimpulan_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\v1\User','nip','vg_nip');
    }

    public function getStatusAttribute($value)
    {
        switch ($this->attributes['level']) {
            case '1':
                return 'Level I (Normal)';
            case '2':
                return 'Level II (Waspada)';
            case '3':
                return 'Level III (Siaga)';
            default:
                return 'Level IV (Awas)';
        }
    }

}
