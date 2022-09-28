<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Carbon\Carbon;

class Vona extends Model
{
    use Uuid;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $appends = [
        'source',
        'contacts'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'is_visible' => 'boolean',
        'is_continuing' => 'boolean',
        'ash_color' => 'array',
        'ash_intensity' => 'array',
        'ash_directions' => 'array',
    ];

    protected $with = [
        'user:nip,name',
    ];

    protected $guarded = [
        'id',
        'uuid',
    ];

    protected $hidden = ['id'];

    const SOURCE = "Indonesian Center for Volcanology and Geological Hazard Mitigation (CVGHM)";

    const CONTACTS = "Center for Volcanology and Geological Hazard Mitigation (CVGHM), Tel: +62-22-727-2606, Facsimile: +62-22-720-2761, email : pvmbg@esdm.go.id";

    // public function getIssuedUtcAttribute()
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s' ,$this->attributes['issued'])->format('Ymd/Hi').'Z';
    // }

    public function getPreviousCodeAttribute($value)
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

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }

    public function pengirim()
    {
        return $this->belongsTo('App\User','nip_pengirim','nip');
    }

    public function ven()
    {
        return $this->belongsTo('App\MagmaVen','ven_uuid','uuid');
    }
}
