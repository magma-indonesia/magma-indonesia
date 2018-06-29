<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVar extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    protected $fillable = [
        'var_nip_pemeriksa_pj',
        'var_nama_pemeriksa_pj'
    ];

    public function getCuStatusAttribute($value)
    {
        switch ($value) {
            case 'Level IV (Awas)':
                return 4;
            case 'Level III (Siaga)':
                return 3;
            case 'Level II (Waspada)':
                return 2;
            default:
                return 1;
        }
    }
}
