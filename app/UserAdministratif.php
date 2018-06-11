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
        'bidang',
        'tanggal_lahir',
        'tempat_lahir',
        'agama',
        'kelamin',
        'status_nikah',
        'jabatan',
        'pangkat',
        'golongan',
        'pendidikan_terakhir',
        'kantor'
    ];

    protected $guarded  = [
        'id'
    ];

    public function getBidangAttribute($value)
    {
        switch ($value) {
            case 1:
                $bidang = 'Pusat Vulkanologi dan Mitigasi Bencana Geologi';
                break;
            case 2:
                $bidang = 'Mitigasi Gunung Api';
                break;
            case 3:
                $bidang = 'Mitigasi Gerakan Tanah';
                break;
            case 4:
                $bidang = 'Mitigasi Gempa Bumi dan Tsunami';
                break;
            case 5:
                $bidang = 'Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi';
                break;
            default:
                $bidang = 'Tata Usaha';
                break;
        }

        return $bidang;
    }
}
