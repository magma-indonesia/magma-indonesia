<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class SigertanVerifikator extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'ver_idx';

    protected $table = 'magma_qls_ver';
}
