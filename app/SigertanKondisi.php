<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanKondisi extends Model
{
    protected $casts = [
        'prakiraan_kerentanan' => 'array',
        'faktor_penyebab' => 'array'
    ];

    protected $fillable = [
        'noticenumber_id',
        'prakiraan_kerentanan',
        'tipe_gerakan',
        'material',
        'arah_longsoran',
        'panjang_total',
        'lebar_massa',
        'panjang_massa',
        'ketebalan_massa',
        'lebar_bidang',
        'panjang_bidang',
        'ketebalan_bidang',
        'faktor_penyebab',
    ];
}
