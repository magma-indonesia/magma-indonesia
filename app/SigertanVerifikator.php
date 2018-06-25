<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanVerifikator extends Model
{
    protected $with = ['user:nip,name'];

    protected $fillable = [
        'noticenumber_id',
        'nip_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip_id','nip');
    }
}
