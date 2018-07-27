<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
    ];

    protected $fillable = [
        'nip_id',
        'kantor_id',
        'checkin',
        'checkin_image',
        'checkin_latitude',
        'checkin_longitude',
        'checkin_distance',
        'checkout',
        'checkout_image',
        'checkout_latitude',
        'checkout_longitude',     
        'distance',
        'duration',
        'nip_verifikator',
        'keterangan'
    ];

    protected $guarded = ['id'];

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip_id','nip');
    }

    /**
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function verifikator()
    {
        return $this->belongsTo('App\User','nip_verifikator','nip');
    }    
    
}
