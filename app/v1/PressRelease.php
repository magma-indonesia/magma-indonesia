<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\Viewable;

class PressRelease extends Model
{
    use Viewable;
    
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'magma_press';

    protected $casts = [
        'datetime' => 'datetime:Y-m-d H:i:s',
        'log' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'datetime',
        'category',
        'judul',
        'sub_judul',
        'tipe',
        'deskripsi',
        'prov',
        'kab',
        'kec',
        'fotolink',
        'file',
        'hit_file',
        'kodega',
        'namaga',
        'press_pelapor',
        'press_editor',
        'soft_delete',
        'sent',
    ];

    protected $guarded = ['id'];

}
