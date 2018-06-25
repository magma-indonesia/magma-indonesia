<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanRekomendasi extends Model
{
    protected $fillable = [
        'noticenumber_id',
        'rekomendasi'
    ];
}
