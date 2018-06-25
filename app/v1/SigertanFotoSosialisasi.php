<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class SigertanFotoSosialisasi extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'sos_idx';

    protected $table = 'magma_qls_sos';
}
