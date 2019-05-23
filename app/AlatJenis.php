<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlatJenis extends Model
{
    protected $fillable = [
        'jenis_alat'
    ];

    public function alat()
    {
        return $this->hasMany('App\Alat','jenis_id','id');
    }
}
