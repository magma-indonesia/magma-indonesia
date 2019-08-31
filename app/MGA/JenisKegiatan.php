<?php

namespace App\MGA;

use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    protected $fillable = [
        'nama'
    ];

    public function kegiatan()
    {
        return $this->hasMany('App\MGA\Kegiatan');
    }
}
