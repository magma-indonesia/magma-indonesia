<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaSigertan extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'idx';

    protected $table = 'magma_sigertan';

    protected $with = [ 
        'anggota','foto_kejadian',
        'foto_sosialisasi','rekomendasi','status','verifikator'
    ];

    /**
     * Satuan Bentang Alam
     *
     * @param string $value
     * @return void
     */
    public function getQlsSbaAttribute(string $value)
    {
        return empty($value) ? null : explode('#',$value);
    }

    /**
     * Kemiringan lereng kualitatif (pilihan: 
     * "Datar (flat) : 0 - 2 derajat", 
     * "Agak miring (gently slope) : 2 - 4 derajat",
     * "Miring (sloping) : 4 - 8 derajat",
     * "Agak curam (moderately steep) : 8 - 16 derajat", 
     * "Curam (steep) : 16 - 35 derajat",
     * "Sangat curam (very steep) : 35 - 55 derajat",
     * "Hampir tegak (extremely steep) : > 55 derajat"
     *
     * @param string $value
     * @return void
     */
    public function getQlsMrlAttribute(string $value)
    {
        return empty($value) ? null : explode('#',$value);
    }

    /**
     * Jenis Batuan
     *
     * @param string $value
     * @return void
     */
    public function getQlsJbtAttribute(string $value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * Formasi Batuan
     *
     * @param string $value
     * @return void
     */
    public function getQlsFrmAttribute(string $value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * Struktur Geologi
     *
     * @param string $value
     * @return void
     */
    public function getQlsStrAttribute(string $value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * Jenis tanah pelapukan
     *
     * @param string $value
     * @return void
     */
    public function getQlsJtpAttribute(string $value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * Tipe keairan
     *
     * @param string $value
     * @return void
     */
    public function getQlsAirAttribute(string $value)
    {
        return empty($value) ? null : explode('#',$value);
    }

    /**
     * Tipe gerakan tanah
     *
     * @param string $value
     * @return void
     */
    public function getQlsTgtAttribute(string $value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * Material gerakan tanah
     *
     * @param string $value
     * @return void
     */
    public function getQlsMgtAttribute(string $value)
    {
        return empty($value) ? null : $value;
    }

    /**
     * Tata guna lahan
     *
     * @param string $value
     * @return void
     */
    public function getQlsTglAttribute(string $value)
    {
        return empty($value) ? null : explode('#',$value);
    }

    /**
     * Formasi Prakiraan kerentanan gerakan tanah 
     * (pilihan: Rendah, Menengah, Tinggi)
     *
     * @param string $value
     * @return void
     */
    public function getQlsZkgAttribute(string $value)
    {
        return empty($value) ? null : explode('#',$value);
    }

    /**
     * Faktor penyebab gerakan tanah
     *
     * @param string $value
     * @return void
     */
    public function getQlsCauAttribute(string $value)
    {
        return empty($value) ? null : explode('#',$value);
    }

    /**
     * Laporan kejadian dari aplikasi
     * Community Reporting System
     * 
     * @return void
     */
    public function crs()
    {
        return $this->belongsTo('App\v1\GertanCrs','crs_ids','crs_ids');
    }

    /**
     * Anggota tim
     *
     * @return void
     */
    public function anggota()
    {
        return $this->hasMany('App\v1\SigertanAnggotaTim','qls_ids','qls_ids');
    }

    /**
     * Foto kejadian
     *
     * @return void
     */
    public function foto_kejadian()
    {
        return $this->hasMany('App\v1\SigertanFotoKejadian','qls_ids','qls_ids');
    }

    public function foto_sosialisasi()
    {
        return $this->hasMany('App\v1\SigertanFotoSosialisasi','qls_ids','qls_ids');
    }

    public function rekomendasi()
    {
        return $this->hasOne('App\v1\SigertanRekomendasi','qls_ids','qls_ids');
    }

    public function status()
    {
        return $this->hasOne('App\v1\SigertanStatus','qls_ids','qls_ids');
    }

    public function verifikator()
    {
        return $this->hasOne('App\v1\SigertanVerifikator','qls_ids','qls_ids');
    }
}
