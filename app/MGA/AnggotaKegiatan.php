<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Integer;

class AnggotaKegiatan extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'detail_kegiatan_id',
        'nip_anggota',
        'start_date',
        'end_date',
        'uang_harian',
        'penginapan_tigapuluh',
        'penginapan_penuh',
        'jumlah_hari_menginap',
        'uang_transport',
        'nip_kortim'
    ];

    protected $appends = [
        'uang_harian_total',
        'uang_penginapan_total',
        'jumlah_hari',
        'penginapan_tigapuluh_total',
        'penginapan_penuh_total',
        'total_biaya'
    ];

    protected function jumlah_hari() : int
    {
        $start = Carbon::parse($this->attributes['start_date']);
        $end = Carbon::parse($this->attributes['end_date']);
        return (int) $end->diffInDays($start)+1;
    }

    protected function jumlah_penginapan_tigapuluh() : int
    {
        $jumlah_hari = $this->jumlah_hari();
        return (int) $jumlah_hari-$this->attributes['jumlah_hari_menginap']-1;
    }

    public function kegiatan()
    {
        return $this->belongsTo('App\MGA\Kegiatan');
    }

    public function detail_kegiatan()
    {
        return $this->belongsTo('App\MGA\DetailKegiatan');
    }

    public function getJumlahHariAttribute()
    {
        return $this->jumlah_hari();
    }

    public function getPenginapanTigapuluhTotalAttribute()
    {
        return $this->jumlah_penginapan_tigapuluh()*$this->attributes['penginapan_tigapuluh']*0.3;
    }

    public function getPenginapanPenuhTotalAttribute()
    {
        return $this->attributes['penginapan_penuh']*$this->attributes['jumlah_hari_menginap'];
    }

    public function getUangHarianTotalAttribute()
    {
        $jumlah_hari = $this->jumlah_hari();

        return $jumlah_hari*$this->attributes['uang_harian'];
    }

    public function getUangPenginapanTotalAttribute()
    {
        return $this->getPenginapanTigapuluhTotalAttribute()+
                $this->getPenginapanPenuhTotalAttribute();
    }

    public function getTotalBiayaAttribute()
    {
        return $this->attributes['uang_transport']+
                $this->getUangHarianTotalAttribute()+
                $this->getPenginapanTigapuluhTotalAttribute()+
                $this->getPenginapanPenuhTotalAttribute();
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip_anggota','nip');
    }
}
