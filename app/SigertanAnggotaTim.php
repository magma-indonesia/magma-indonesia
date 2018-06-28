<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanAnggotaTim extends Model
{
    protected $with = ['user:nip,name'];

    protected $fillable = [
        'noticenumber_id',
        'nip_id'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip_id','nip');
    }

}
