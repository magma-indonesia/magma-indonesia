<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;

class BiayaKegiatan extends Model
{
    protected $fillable = [
        'detail_kegiatan_id',
        'upah',
        'bahan',
        'carter',
        'bahan_lainnya',
        'nip_kortim'
    ];

    protected $appends = [
        'total_biaya'
    ];

    public function detail_kegiatan()
    {
        return $this->belongsTo('App\MGA\DetailKegiatan');
    }

    public function getTotalBiayaAttribute()
    {
        return $this->attributes['upah']+
                $this->attributes['bahan']+
                $this->attributes['carter']+
                $this->attributes['bahan_lainnya'];
    }
}
