<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusIndex extends Model
{
    protected $table    = 'statuses_desc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status'
    ];
}
