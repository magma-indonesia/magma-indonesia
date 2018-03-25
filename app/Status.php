<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id',
        'level_id'
    ];

    public function deskriptif()
    {
        return $this->belongsTo('App\StatusIndex','statuses_desc_id','id');
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }
}
