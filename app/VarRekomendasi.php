<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarRekomendasi extends Model
{
    protected $fillable = [
        'code_id',
        'status',
        'rekomendasi'
    ];
}
