<?php

namespace App\v1;

use App\Traits\v1\FilterMagmaVen;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MagmaVen extends Model
{
    use Notifiable;
    use FilterMagmaVen;

    protected $connection = 'magma';

    protected $table = 'magma_erupt';

    protected $primaryKey = 'erupt_id';

    protected $casts = [
        'is_published' => 'boolean',
        'is_blasted' => 'boolean',
        'erupt_tsp' => 'datetime:Y-m-d H:i:s',
        'sent_to_telegram_at' => 'datetime:Y-m-d H:i:s',
        'utc' => 'datetime:Y-m-d H:i:s',
        'erupsi_berlangsung' => 'boolean',
        'vona_created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $timestamps = false;

    protected $guarded = [
        'erupt_id',
    ];

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return config('services.telegram-bot-api.magma_channel');
    }


    /**
     * Merubah status dari string menjadi integer
     *
     * @param string $value
     * @return integer
     */
    protected function getStatus(string $value) : int
    {
        switch ($value) {
            case 'Level IV (Awas)':
                return 4;
            case 'Level III (Siaga)':
                return 3;
            case 'Level II (Waspada)':
                return 2;
            default:
                return 1;
        }
    }

    /**
     * Merubah warna abu dari string menjadi array
     *
     * @param string $value
     * @return array
     */
    public function getEruptWrnAttribute(string $value)
    {
        return $value == '-' ? null : explode(', ',$value);
    }

    /**
     * Merubah intensitas abu dari string menjadi array
     *
     * @param string $value
     * @return array
     */
    public function getEruptIntAttribute(string $value)
    {
        return $value == '-' ? null : explode(', ',$value);
    }

    /**
     * Merubah arah asap dari string menjadi array
     *
     * @param string $value
     * @return array
     */
    public function getEruptArhAttribute(string $value)
    {
        return $value == '-' ? null : explode(', ',$value);
    }

    /**
     * Foto letusan
     *
     * @param string $value
     * @return array
     */
    public function getEruptPhtAttribute(string $value)
    {
        return $value == '-' ? null : $value;
    }

    /**
     * Merubah status string menjadi int
     *
     * @param string $value
     * @return array
     */
    public function getEruptStaAttribute(string $value)
    {
        return $this->getStatus($value);
    }

    /**
     * Merubah nilai empty keterangan menjadi null
     *
     * @param string $value
     * @return array
     */
    public function getEruptKetAttribute(string $value)
    {
        return $value == '-' ? null : $value;
    }

    public function scopeLastVen(Builder $query)
    {
        return $query->orderBy('utc','desc');
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }

    public function user()
    {
        return $this->belongsTo('App\v1\User','erupt_usr','vg_nip');
    }

    public function zoneArea()
    {
        switch ($this->gunungapi->ga_zonearea) {
            case 'WIB':
                return 'Asia/Jakarta';
            case 'WITA':
                return 'Asia/Makassar';
            default:
                return 'Asia/Jayapura';
        }
    }

    public function getVarLogLocalAttribute()
    {
        return Carbon::createFromTimeString($this->attributes['utc'], 'UTC')
            ->setTimezone($this->zoneArea())->format('Y-m-d H:i:s');
    }

    public function getEruptTglLocalAttribute()
    {
        return Carbon::createFromTimeString($this->attributes['utc'], 'UTC')
            ->setTimezone($this->zoneArea());
    }

    public function getEruptTspLocalAttribute()
    {
        return Carbon::createFromTimeString($this->attributes['erupt_tsp'], config('app.timezone'))
            ->setTimezone($this->zoneArea());
    }
}
