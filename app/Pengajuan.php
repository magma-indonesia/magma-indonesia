<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    public $timestamps = false;

    protected $dates = [
        'created_at',
        'answered_at'
    ];

    protected $fillable = [
        'nip_id',
        'topik',
        'pertanyaan',
        'foto_pertanyaan',
        'follow_up',
        'nip_pemroses',
        'jawaban',
        'foto_jawaban',
        'answered_at',
        'created_at'
    ];

    protected $appends = [
        'panel',
        'label'
    ];

    public function pelapor()
    {
        return $this->belongsTo('App\User','nip_id','nip');
    }

    public function penjawab()
    {
        return $this->belongsTo('App\User','nip_pemroses','nip');
    }

    public function getPanelAttribute()
    {
        $value = $this->attributes['follow_up'];
        switch ($value) {
            case 'BELUM DIPROSES':
                return 'hred';
            case 'SEDANG DIPROSES':
                return 'hyellow';
            default:
                return 'hgreen';
        }
    }

    public function getLabelAttribute()
    {
        $value = $this->attributes['topik'];
        switch ($value) {
            case 'ALAT MONITORING':
                return 'label-danger';
            case 'INVENTARIS POS':
                return 'label-warning';
            case 'CUTI/IJIN':
                return 'label-success';
            default:
                return 'label-magma';
        }
    }
}
