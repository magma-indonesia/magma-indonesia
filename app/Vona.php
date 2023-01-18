<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Traits\VonaTrait;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

class Vona extends Model
{
    use Uuid;
    use Notifiable;
    use VonaTrait;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $appends = [
        'issued_utc',
        'issued_local',
        'ash_height_ft',
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
        'issued' => 'datetime:Y-m-d H:i:s',
        'sent_to_telegram' => 'datetime',
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

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return config('services.telegram-bot-api.vona_channel');
    }

    public function getIssuedUtcAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['issued'])->format('Ymd/Hi').'Z';
    }

    public function getIssuedLocalAttribute()
    {
        $zone = $this->gunungapi->zonearea;

        switch ($zone) {
            case 'WIB':
                $zone = 'Asia/Jakarta';
                break;
            case 'WITA':
                $zone = 'Asia/Makassar';
                break;
            default:
                $zone = 'Asia/Jayapura';
        }

        return Carbon::createFromTimeString($this->attributes['issued'], 'UTC')->setTimezone($zone);
    }

    public function getPreviousCodeAttribute($value)
    {
        return strtolower($value);
    }

    public function getAshHeightFtAttribute()
    {
        return round($this->attributes['ash_height'] * 0.3048);
    }

    public function getSourceAttribute()
    {
        return self::SOURCE;
    }

    public function getContactsAttribute()
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

    public function old_ven()
    {
        return $this->belongsTo('App\v1\MagmaVen', 'old_ven_uuid', 'uuid');
    }
}
