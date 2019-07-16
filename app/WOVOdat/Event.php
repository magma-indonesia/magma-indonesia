<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'wovo';

    protected $table = 'co_event';

    protected $primaryKey = 'co_event_id';

    public $timestamps = 'false';
}
