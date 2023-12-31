<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAdministratif extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bidang_id',
        'tanggal_lahir',
        'tempat_lahir',
        'agama',
        'kelamin',
        'status_nikah',
        'jabatan_id',
        'fungsional_id',
        'pendidikan_terakhir',
        'jurusan_terakhir',
        'kantor_id'
    ];

    protected $guarded  = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bidang()
    {
        return $this->belongsTo('App\UserBidang','bidang_id','id');
    }

    public function jabatan()
    {
        return $this->belongsTo('App\Jabatan');
    }

    public function fungsional()
    {
        return $this->belongsTo('App\Fungsional');
    }

    public function golongan()
    {
        return $this->belongsTo('App\Golongan');
    }

    public function kantor()
    {
        return $this->belongsTo('App\Kantor','kantor_id','code');
    }
}
