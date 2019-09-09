<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaporanGuguran extends Model
{

    protected $casts = [
        'datetime' => 'datetime',
        'arah_guguran' => 'array',
        'arah_angin' => 'array'
    ];

    protected $fillable = [
        'code_id',
        'datetime',
        'jarak_luncur',
        'volume',
        'tinggi_kolom',
        'kecepatan',
        'arah_guguran',
        'arah_angin',
        'durasi',
        'amplitudo',
        'kecepatan',
        'keterangan_lainnya',
        'photo_1',
        'photo_2',
        'photo_3',
        'photo_4',
        'nip_pelapor'
    ];

    protected $guarded  = [
        'id'
    ];

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

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }
}
