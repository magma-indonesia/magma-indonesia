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
        'wasap'         => 'array',
        'intensitas'    => 'array',
        'arah_asap'     => 'array',
    ];

    protected $dates = [
        'date'
    ];

    protected $with = [
        'user:nip,name'
    ];

    protected $appends = [
        'status_deskripsi'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id',
        'date', 
        'visibility',
        'height',
        'wasap',
        'intensitas',
        'arah_asap',
        'amplitudo',
        'durasi',        
        'photo',
        'status',
        'rekomendasi',
        'lainnya',
        'nip_pelapor',
        'created_at'
    ];

    protected $guarded = ['id','uuid'];

    protected $hidden = ['id','nip_pelapor'];

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
}
