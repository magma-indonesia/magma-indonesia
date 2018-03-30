<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldRoq extends Model
{
    public $timestamps = false;

    protected $connection = 'magma';

    protected $primaryKey = 'no';

    protected $table = 'magma_roq';

    protected $fillable = [

        'roq_tanggapan',
        'roq_title',
        'roq_tsu',
        'roq_intro',
        'roq_konwil',
        'roq_mekanisme',
        'roq_efek',
        'roq_rekom',
        'roq_source',
        'roq_maplink',
        'roq_nama_pelapor',
        'roq_nip_pelapor',
        'roq_nama_pemeriksa',
        'roq_nip_pemeriksa',
        'roq_logtime'

    ];

}
