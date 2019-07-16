<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $connection = 'wovo';

    protected $table = 'co_bulletin';

    protected $primaryKey = 'co_bulletin_id';

    public $timestamps = 'false';

    public function events()
    {
        return $this->hasMany('App\WOVOdat\Event','co_bulletin_id','co_bulletin_id');
    }
}
