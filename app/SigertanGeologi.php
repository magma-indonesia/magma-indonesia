<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanGeologi extends Model
{
    protected $casts = [
        'bentang_alam' => 'array',
        'kemiringan_lereng' => 'array',
        'tipe_keairan' => 'array',
        'tata_guna_lahan' => 'array'
    ];

    protected $fillable = [
        'noticenumber_id',
        'bentang_alam',
        'kemiringan_lereng',
        'kemiringan_lerang_rata',
        'ketinggian',
        'jenis_batuan',
        'formasi_batuan',
        'struktur_geologi',
        'jenis_tanah',
        'ketebalan_tanah',
        'tipe_keairan',
        'muka_air_tanah',
        'tata_guna_lahan'
    ];
}
