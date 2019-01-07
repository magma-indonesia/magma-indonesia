<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class VonaSubscriber extends Model
{
    public $timestamps = false;

    protected $connection = 'magma';

    protected $table = 'magma_subscribe';

    protected $primaryKey = 'no';

    protected $guarded = [
        'no'
    ];

    protected $fillable = [
        'nama',
        'email',
        'list',
        'subscribe'
    ];

    public function getNamaAttribute($value)
    {
        return !empty($value) ? $value : 'Guest';
    }
}
