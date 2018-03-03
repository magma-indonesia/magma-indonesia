<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarDaily extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


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
