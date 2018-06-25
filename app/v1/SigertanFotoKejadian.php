<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class SigertanFotoKejadian extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'fst_idx';

    protected $table = 'magma_qls_fst';
}
