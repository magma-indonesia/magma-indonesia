<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MagmaSigertan extends Model
{
    /**
     * Set to false, so it can import MAGMA v1 original datetime value
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Autocast to date
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Default eager loading when calling this model
     *
     * @var array
     */
    protected $with = [
        'ketua:nip,name',
        'anggota.user:nip,name',
    ];

    /**
     * Fillable column 
     *
     * @var array
     */
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

    /**
     * Protect column from mass save
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Semua laporan Qls hanya dimiliki oleh 1 laporan Crs
     *
     * @return void
     */
    public function crs()
    {
        return $this->belongsTo('App\SigertanCrs','crs_id','crs_id');
    }

    /**
     * Qls hanya memiliki 1 model Geologi
     *
     * @return void
     */
    public function geologi()
    {
        return $this->hasOne('App\SigertanGeologi','noticenumber_id','noticenumber');
    }

    /**
     * QLs hanya memiliki 1 data informasi kondisi kejadian gerakan tanah 
     *
     * @return void
     */
    public function kondisi()
    {
        return $this->hasOne('App\SigertanKondisi','noticenumber_id','noticenumber');
    }

    /**
     * QLs hanya memiliki 1 data kerusakan
     *
     * @return void
     */
    public function kerusakan()
    {
        return $this->hasOne('App\SigertanKerusakan','noticenumber_id','noticenumber');
    }

    /**
     * Setiap kejadian gerakan tanah memiliki 1 Rekomendasi
     *
     * @return void
     */
    public function rekomendasi()
    {
        return $this->hasOne('App\SigertanRekomendasi','noticenumber_id','noticenumber');
    }

    /**
     * Tanggapan Kejadian gerakan tanah dipimipin oleh 1 Ketua Tim 
     *
     * @return void
     */
    public function ketua()
    {
        return $this->belongsTo('App\User','nip_ketua','nip');
    }

    /**
     * Masing-masing tim tanggap darurat bisa memiliki lebih dari 1 Anggota Tim
     *
     * @return void
     */
    public function anggota()
    {
        return $this->hasMany('App\SigertanAnggotaTim','noticenumber_id','noticenumber');
    }

    /**
     * Verifikator laporan hanya bisa dilakukan oleh pejabat struktural
     *
     * @return void
     */
    public function verifikator()
    {
        return $this->hasOne('App\SigertanVerifikator','noticenumber_id','noticenumber');
    }

    /**
     * Status terbit laporan yang dibuat oleh Tim Gerakan Tanah
     *
     * @return void
     */
    public function status()
    {
        return $this->hasOne('App\SigertanStatus','noticenumber_id','noticenumber');
    }

    /**
     * Setiap kejadian gerakan tanah bisa memiliki lebih dari 1 foto kejadian
     *
     * @return void
     */
    public function fotoKejadian()
    {
        return $this->hasMany('App\SigertanFotoKejadian','noticenumber_id','noticenumber');
    }

    /**
     * Setiap kejadian gerakan tanah bisa memiliki lebih dari 1 foto sosialisasi
     *
     * @return void
     */
    public function fotoSosialisasi()
    {
        return $this->hasMany('App\SigertanFotoSosialisasi','noticenumber_id','noticenumber');
    }
}
