<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*  
 *  
 *  Digunakan hanya untuk Import
 *  Data Gempa Gunung Api dari MAGMA v1 ke MAGMA v2
 *  Tidak dibutuhkan untuk MAGMA v2
 * 
*/
class GempaSP extends Model
{

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($data)
    {
        $this->table = $data;
    }

    //IMPORTANT
    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);
    
        $model->setTable($this->getTable());
    
        return $model;
    }

    protected $fillable = [

        'var_gempa_id',
        'noticenumber_id',
        'jumlah',
        'amin',
        'amax',
        'spmin',
        'spmax',
        'dmin',
        'dmax',
        'created_at',
        'updated_at',
        'deleted_at'
        
    ];

    protected $hidden   = [
        
        'id'
        
    ];
        
    protected $guarded  = [

        'id'

    ];

    public function vars()
    {
        return $this->belongsTo('App\MagmaVar','noticenumber_id','noticenumber');
    }

}
