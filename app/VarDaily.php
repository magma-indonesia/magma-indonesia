<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarDaily extends Model
{

    protected $fillable = [

        'code_id',
        'noticenumber_id',
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    protected $guarded  = [
        'id'
    ];

    public function var()
    {
        return $this->belongsTo('App\MagmaVar','noticenumber_id','noticenumber');
    }
}
