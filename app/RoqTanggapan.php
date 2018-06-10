<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoqTanggapan extends Model
{
    protected $casts = [
        'sumber' => 'array'
    ];

    public $incrementing = false;

    protected $primaryKey = 'noticenumber_id';

    protected $with = ['pelapor:nip,name','pemeriksa:nip,name'];

    protected $fillable = [
        'noticenumber_id',
        'judul',
        'tsunami',
        'pendahuluan',
        'kondisi_wilayah',
        'mekanisme',
        'dampak',
        'rekomendasi',
        'sumber',
        'sumber',
        'nip_pelapor',
        'nip_pemeriksa'
    ];

    protected $guarded = ['id','noticenumber_id'];

    protected $hidden = ['id','nip_pelapor','nip_pemeriksa'];

    public function pelapor()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }

    public function pemeriksa()
    {
        return $this->belongsTo('App\User','nip_pemeriksa','nip');
    }
}
