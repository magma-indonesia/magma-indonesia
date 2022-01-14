<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Viewable;

class MagmaVen extends Model
{
    use Uuid,Viewable;
    //
    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $casts = [
        'warna_abu' => 'array',
        'intensitas' => 'array',
        'arah_abu' => 'array',
    ];

    protected $dates = [
        'datetime_utc'
    ];

    protected $with = [
        'user:nip,name'
    ];

    protected $appends = [
        'status_deskripsi'
    ];

    protected $guarded = [
        'id',
        'uuid',
    ];

    protected $hidden = [
        'id',
        'nip_pelapor',
    ];

    /**
     * Scope a status query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getStatusDeskripsiAttribute($value)
    {
        switch ($value) {
            case 4:
                return 'Level IV (Awas)';
            case 3:
                return 'Level III (Siaga)';
            case 2:
                return 'Level II (Waspada)';
            default:
                return 'Level I (Normal)';
        }
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }

    public function vona()
    {
        return $this->hasOne('App\Vona','ven_uuid','uuid');
    }

    /**
     * Mendapatkan rekomendasi langsung dari Data Dasar
     *
     * @return void
     */
    public function rekomendasi()
    {
        return $this->belongsTo('App\VarRekomendasi', 'rekomendasi_id', 'id');
    }

}
