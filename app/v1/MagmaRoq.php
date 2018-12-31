<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaRoq extends Model
{
    public $timestamps = false;

    protected $connection = 'magma';

    protected $primaryKey = 'no';

    protected $table = 'magma_roq';

    protected $guarded = [
        'no'
    ];

    protected $fillable = [
        'datetime_wib',
        'id_lap',
        'datetime_wib_str',
        'datetime_utc',
        'magnitude',
        'magtype',
        'depth',
        'dep_unit',
        'lon_lima',
        'lat_lima',
        'latlon_text',
        'area',
        'koter',
        'mmi',
        'nearest_volcano',
        'roq_tanggapan',
        'roq_title',
        'roq_tsu',
        'roq_intro',
        'roq_konwil',
        'roq_mekanisme',
        'roq_efek',
        'roq_rekom',
        'roq_source',
        'roq_nama_pelapor',
        'roq_nip_pelapor',
        'roq_nama_pemeriksa',
        'roq_nip_pemeriksa',
    ];

}
