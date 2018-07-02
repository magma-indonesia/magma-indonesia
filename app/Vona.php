<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;
use CyrildeWit\PageViewCounter\Traits\HasPageViewCounter;

class Vona extends Model
{
    use Uuid,HasPageViewCounter;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $appends = [
        'issued_utc',
        'source',
        'contacts',
        'page_views'
    ];

    protected $casts = [
        'sent' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'noticenumber',
        'issued',
        'type', 
        'code_id',
        'cu_code',
        'prev_code',
        'location',
        'vas',
        'vch_summit',        
        'vch_asl',
        'vch_other',
        'remarks',
        'sent',
        'nip_pelapor',
        'nip_pengirim'
    ];

    protected $guarded = ['id','uuid'];

    protected $hidden = ['id'];

    const SOURCE = "Indonesian Center for Volcanology and Geological Hazard Mitigation (CVGHM)";

    const CONTACTS = "Center for Volcanology and Geological Hazard Mitigation (CVGHM), Tel: +62-22-727-2606, Facsimile: +62-22-720-2761, email : vsi@vsi.esdm.go.id";

    public function getIssuedUtcAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s' ,$this->attributes['issued'])->format('Ymd/Hi').'Z';
    }

    public function getPrevCodeAttribute($value)
    {
        return strtolower($value);
    }

    public function getSourceAttribute($value)
    {
        return self::SOURCE;
    }

    public function getContactsAttribute($value)
    {
        return self::CONTACTS;
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

    public function ven()
    {
        return $this->belongsTo('App\MagmaVen','ven_uuid','uuid');
    }
}
