<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class IntervalSwarm extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sd_ivl';

    protected $primaryKey = 'sd_ivl_id';

    public $timestamps = 'false';
}
