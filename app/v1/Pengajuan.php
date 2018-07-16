<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'pengajuan';

    public function getTopikAttribute($value)
    {
        return strtoupper($value);
    }

    public function getFollowupAttribute($value)
    {
        switch ($value) {
            case 'Belum Diproses':
                return 'BELUM DIPROSES';
            case 'Sedang Diproses':
                return 'SEDANG DIPROSES';
            default:
                return 'SUDAH DIPROSES';
        }
    }

    public function getBukti1Attribute($value)
    {
        return empty($value) ? null : $value;
    }

    public function getBukti2Attribute($value)
    {
        return empty($value) ? null : $value;
    }

    public function getOlehNipAttribute($value)
    {
        return empty($value) ? null : $value;
    }

    public function getJawabAttribute($value)
    {
        return empty($value) ? null : $value;
    }

    public function getWaktuJawabAttribute($value)
    {
        return empty($value) ? null : $value;
    }
}
