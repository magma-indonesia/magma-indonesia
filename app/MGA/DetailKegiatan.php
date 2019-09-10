<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    protected $appends = [
        'biaya_kegiatan_total',
        'biaya_tim_total',
        'jumlah_hari',
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    protected function jumlah_hari() : int
    {
        $start = Carbon::parse($this->attributes['start_date']);
        $end = Carbon::parse($this->attributes['end_date']);
        return (int) $end->diffInDays($start)+1;
    }

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

    public function biaya_kegiatan()
    {
        return $this->hasOne('App\MGA\BiayaKegiatan');
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    public function anggota_tim()
    {
        return $this->hasMany('App\MGA\AnggotaKegiatan');
    }

    public function getBiayaKegiatanTotalAttribute()
    {
        return $this->biaya_kegiatan()->first()->total_biaya+
                $this->anggota_tim()->get()->sum('total_biaya');
    }

    public function getBiayaTimTotalAttribute()
    {
        return $this->anggota_tim()->get()->sum('total_biaya');
    }

    public function getJumlahHariAttribute()
    {
        return $this->jumlah_hari();
    }

    public function getTanggalMulaiAttribute()
    {
        return Carbon::parse($this->attributes['start_date'])->formatLocalized('%A, %d %B %Y');
    }

    public function getTanggalAkhirAttribute()
    {
        return Carbon::parse($this->attributes['end_date'])->formatLocalized('%A, %d %B %Y');
    }
}
