<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarDaily extends Model
{

    protected $fillable = [

        'code_id',
        'noticenumber_id',
    ];

    protected $guarded  = [
        'id'
    ];

    public function var()
    {
        return $this->belongsTo('App\MagmaVar','noticenumber_id','noticenumber');
    }
}
