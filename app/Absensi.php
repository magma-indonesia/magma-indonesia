<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
    ];

    protected $fillable = [
        'nip_id',
        'kantor_id',
        'checkin',
        'checkin_image',
        'checkin_latitude',
        'checkin_longitude',
        'checkin_distance',
        'checkout',
        'checkout_image',
        'checkout_latitude',
        'checkout_longitude',     
        'distance',
        'duration',
        'nip_verifikator',
        'keterangan'
    ];

    protected $appends = [
        'keterangan_label',
        'keterangan_tambahan'
    ];

    protected $guarded = ['id'];

    public function getKeteranganTambahanAttribute()
    {
        $tambahan = [];
        if ($this->attributes['checkout'])
        {
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['checkin']);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['checkout']);

            $start->diffInHours($end) <7.6 ?
                    $tambahan[] = 'Jam kerja kurang dari 7.5 jam':
                    false;
        } else {
            $tambahan[] = 'Belum checkout';
        }

        $this->attributes['checkin_distance'] > 500 ? 
                $tambahan[] = 'Radius checkin lebih dari 500m' :
                false;

        $this->attributes['distance'] > 500 ?
                $tambahan[] = 'Radius checkout lebih dari 500m':
                false;

        return $tambahan;
    }

    public function getKeteranganLabelAttribute()
    {
        switch ($this->attributes['keterangan']) {
            case 0:
                return '<span class="label label-danger">Alpha</span>';
            case 1:
                return '<span class="label label-success">Hadir</span>';
            case 2:
                return '<span class="label label-info">Libur</span>';
            case 3:
                return '<span class="label label-info">Izin</span>';
            case 4:
                return '<span class="label label-info">Sakit</span>';
            case 5:
                return '<span class="label label-info">Kursus</span>';
            case 6:
                return '<span class="label label-info">Pendidikan dan Latihan</span>';
            case 7:
                return '<span class="label label-info">Dinas Luar Kota (SPPD)</span>';
            case 8:
                return '<span class="label label-info">Dinas Dalam/Luar Kota (Non SPPD)</span>';
            case 9:
                return '<span class="label label-info">Dinas Dalam Kota</span>';
            case 10:
                return '<span class="label label-info">Cuti Tahunan</span>';
            case 11:
                return '<span class="label label-info">Cuti Alasan Penting</span>';
            case 12:
                return '<span class="label label-info">Cuti Besar</span>';
            case 13:
                return '<span class="label label-info">Cuti Diluar Tanggungan Negara</span>';
            case 14:
                return '<span class="label label-info">Cuti Sakit</span>';
            case 15:
                return '<span class="label label-info">Pulang Cepat dengan Alasan</span>';
            case 16:
                return '<span class="label label-info">Lupa Absen Datang</span>';
            case 17:
                return '<span class="label label-info">Lupa Absen Pulang</span>';
            case 18:
                return '<span class="label label-info">Diperbantukan</span>';
            case 19:
                return '<span class="label label-info">Darurat/Bencana (Force Majure)/Kebijakan Pimpinan</span>';
            case 20:
                return '<span class="label label-info">Masa Persiapan Pensiun (MPP)</span>';
            case 21:
                return '<span class="label label-info">Diliburkan Pemerintah Setempat</span>';
            case 22:
                return '<span class="label label-info">Bebas Sementara dari Pegawai Negeri Sipil</span>';
            default:
                return '<span class="label label-danger">Tidak ada dalam daftar</span>';
                break;
        }
    }

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip_id','nip');
    }

    /**     
     *   Masing-masing User hanya memiliki
     *   1 kantor
     * 
     *   @return \App\User 
     * 
     */
    public function kantor()
    {
        return $this->belongsTo('App\Kantor','kantor_id','code');
    }

    /**
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function verifikator()
    {
        return $this->belongsTo('App\User','nip_verifikator','nip');
    }    
    
}
