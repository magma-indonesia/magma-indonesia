<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    // 1. Seismometer
    // 2. Tiltmeter
    // 3. GPS
    // 4. EDM
    // 5. CTD
    // 6. DOAS
    // 7. MultiGas
    // 8. CCTV
    // 9. Infrasound
    // 10. Repeater
    // 11. Rain Gauge

    protected $fillable = [
        'code_id',
        'jenis_id',
        'kode_alat',
        'nama_alat',
        'latitude',
        'longitude',
        'elevasi',
        'status',
        'nip'
    ];

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 Gunungapi
     * 
     *   @return \App\Gadd
     * 
     */
    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    public function jenis()
    {
        return $this->belongsTo('App\AlatJenis','jenis_id','id');
    }
}
