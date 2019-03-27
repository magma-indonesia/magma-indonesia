<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Viewable;

class Vona extends Model
{
    use Viewable;

    protected $connection = 'magma';

    protected $issued, $year, $month, $day, $hour, $minute;

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'ga_vona';

    protected $fillable = [
        'issued',
        'issued_time',
        'type',
        'vona_image',
        'ga_nama_gapi',
        'ga_id_smithsonian',
        'ga_code',
        'cu_avcode',
        'pre_avcode',
        'source',
        'notice_number',
        'volcano_location',
        'area',
        'summit_elevation',
        'volcanic_act_summ',
        'vc_height',
        'vc_height_text',
        'other_vc_info',
        'remarks',
        'contacts',
        'next_notice',
        'sent',
        'log',
        'nip',
        'nama',
        'sender'
    ];

    protected $guard = ['no'];

    public function getIssuedTimeAttribute()
    {
        $value = $this->attributes['issued'];
        $this->year = substr($value,0,4);
        $this->month = substr($value,4,2);
        $this->day = substr($value,6,2);
        $this->hour = substr($value,9,2);
        $this->minute = substr($value,11,2);
        $this->issued = $this->year.'-'.$this->month.'-'.$this->day.' '.$this->hour.':'.$this->minute;
        // return Carbon::now()->setTimezone('UTC');
        return Carbon::createFromFormat('Y-m-d H:i', $this->issued)->format('Y-m-d H:i:s');
    }

    public function getVcHeightAttribute($value)
    {
        return intval($value);
    }

    public function getSummitElevationAttribute($value)
    {
        return intval($value);
    }

}
