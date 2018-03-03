<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [

        'code_id',
        'body',
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    public function gadd()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }
}
