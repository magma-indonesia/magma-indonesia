<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Vona extends Model
{
    use Uuid;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';
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

    // protected $appends = ['source','contacts'];

    const SOURCE = "Indonesian Center for Volcanology and Geological Hazard Mitigation (CVGHM)";

    const CONTACTS = "Center for Volcanology and Geological Hazard Mitigation (CVGHM)\r\nTel: +62-22-727-2606\r\nFacsimile: +62-22-720-2761\r\nEmail : vsi@vsi.esdm.go.id";

    public function gunungapi ()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    public function user ()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }


}
