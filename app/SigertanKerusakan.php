<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanKerusakan extends Model
{
    protected $fillable = [
        'noticenumber_id',
        'meninggal',
        'luka',
        'rumah_rusak',
        'rumah_hancur',
        'rumah_terancam',
        'bangunan_rusak',
        'bangunan_hancur',
        'bangunan_terancam',
        'lahan_rusak',
        'jalan_rusak',
    ];
}
