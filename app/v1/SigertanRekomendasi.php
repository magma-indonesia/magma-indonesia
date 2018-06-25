<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class SigertanRekomendasi extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'rec_idx';

    protected $table = 'magma_qls_rec';
}
