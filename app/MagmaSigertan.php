<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MagmaSigertan extends Model
{
    public $timestamps = false;

    protected $with = ['crs'];

    protected $fillable = [
        'crs_id',
        'noticenumber',
        'peta_lokasi',
        'peta_geologi',
        'peta_situasi',
        'disposisi',
        'nip_ketua',
        'created_at',
        'updated_at'
    ];

    public function crs()
    {
        return $this->belongsTo('App\SigertanCrs','crs_id','crs_id');
    }

    public function geologi()
    {
        return $this->hasOne('App\SigertanGeologi','noticenumber_id','noticenumber');
    }

    public function kondisi()
    {
        return $this->hasOne('App\SigertanKondisi','noticenumber_id','noticenumber');
    }

    public function kerusakan()
    {
        return $this->hasOne('App\SigertanKerusakan','noticenumber_id','noticenumber');
    }

    public function rekomendasi()
    {
        return $this->hasOne('App\SigertanRekomendasi','noticenumber_id','noticenumber');
    }

    public function anggota()
    {
        return $this->hasMany('App\SigertanAnggotaTim','noticenumber_id','noticenumber');
    }

    public function verifikator()
    {
        return $this->hasOne('App\SigertanVerifikator','noticenumber_id','noticenumber');
    }

    public function status()
    {
        return $this->hasOne('App\SigertanStatus','noticenumber_id','noticenumber');
    }

    public function fotoKejadian()
    {
        return $this->hasMany('App\SigertanFotoKejadian','noticenumber_id','noticenumber');
    }

    public function fotoSosialisasi()
    {
        return $this->hasMany('App\SigertanFotoSosialisasi','noticenumber_id','noticenumber');
    }
}
