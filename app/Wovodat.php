<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wovodat extends Model
{
    protected $connection = 'wovo';

    protected $table = 'co_bulletin';

    protected $primaryKey = 'co_bulletin_id';

    public $timestamps = 'false';
    
}
