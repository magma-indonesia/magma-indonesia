<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class FieldsStation extends Model
{
    protected $connection = 'wovo';

    protected $table = 'fs';

    protected $primaryKey = 'fs_id';

    public $timestamps = 'false';

    public function common_network()
    {
        return $this->belongsTo('App\WOVOdat\CommonNetwork','cn_id','cn_id');
    }
}
