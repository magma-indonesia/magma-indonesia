<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;
use CyrildeWit\PageViewCounter\Traits\HasPageViewCounter;

class MagmaVen extends Model
{
    use Uuid,HasPageViewCounter;
    //
    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';
    
    protected $casts = [
        'wasap'         => 'array',
        'intensitas'    => 'array',
        'arah_asap'     => 'array',
        'date'          => 'datetime:Y-m-d',
    ];

    protected $with = ['user:nip,name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id',
        'vona_uuid',
        'date', 
        'time',
        'height',
        'wasap',
        'intensitas',
        'visibility',
        'arah_asap',
        'amplitudo',
        'durasi',        
        'photo',
        'status',
        'rekomendasi',
        'lainnya',
        'nip_pelapor'
    ];

    protected $guarded = ['id','uuid'];

    protected $hidden = ['id','nip_pelapor'];

    /**
     * Scope a status query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 1:
                $status = 'Level I (Normal)';
                break;
            case 2:
                $status = 'Level II (Waspada)';
                break;
            case 3:
                $status = 'Level III (Siaga)';
                break;
            default:
                $status = 'Level IV (Awas)';
                break;
        }

        return $status;
    }

    /**
     * Get the total page views of the article.
     *
     * @return int
     */
    public function getPageViewsAttribute()
    {
        return $this->getPageViews();
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
