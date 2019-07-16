<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class VolcanoInformation extends Model
{
    protected $connection = 'wovo';

    protected $table = 'vd_inf';

    protected $primaryKey = 'vd_inf_id';

    public $timestamps = 'false';
}
