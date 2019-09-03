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

    public function detail_kegiatan()
    {
        return $this->belongsTo('App\MGA\DetailKegiatan');
    }
}
