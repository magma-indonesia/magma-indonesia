<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'jenis_kegiatan_id',
        'tahun',
        'unique',
        'target_jumlah',
        'target_anggaran',
        'nip_kortim'
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
        return $this->belongsTo('App\User','nip_kortim','nip');
    }

    public function jenis_kegiatan()
    {
        return $this->belongsTo('App\MGA\JenisKegiatan');
    }

    public function detail_kegiatan()
    {
        return $this->hasMany('App\MGA\DetailKegiatan');
    }
}
