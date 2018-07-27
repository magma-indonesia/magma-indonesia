<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'vg_nip';

    protected $table = 'pga_pos';

    protected $fillable = [
        'vg_nip',
        'ga_code',
        'obscode'
    ];
}
