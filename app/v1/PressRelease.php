<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\Viewable;

class PressRelease extends Model
{
    use Viewable;

    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'magma_press';

    protected $casts = [
        'datetime' => 'datetime:Y-m-d H:i:s',
        'log' => 'datetime:Y-m-d H:i:s',
    ];

    protected $guarded = ['id'];

}
