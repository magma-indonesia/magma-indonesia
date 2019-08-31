<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;

class DetailKegiatan extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'code_id',
        'lokasi_lainnya',
        'start_date',
        'end_date',
        'proposal',
        'laporan',
        'nip_ketua',
        'nip_kortim'
    ];

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function ketua()
    {
        return $this->belongsTo('App\User','nip_ketua','nip');
    }

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function kortim()
    {
        return $this->belongsTo('App\User','nip_kortim','nip');
    }

    public function kegiatan()
    {
        return $this->belongsTo('App\MGA\Kegiatan');
    }
}
