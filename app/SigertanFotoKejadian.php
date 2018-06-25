<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanFotoKejadian extends Model
{
    protected $fillable = [
        'noticenumber_id',
        'filename'
    ];
}
