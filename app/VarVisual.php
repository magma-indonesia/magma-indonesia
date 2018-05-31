<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarVisual extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'visibility'    => 'array'
    ];

    protected $fillable = [
        'noticenumber_id',
        'visibility',
        'visual_asap',
        'visual_kawah'
    ];

    protected $hidden  = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Find Visibility 
     * @var $type = Jelas, Kabut 0-I, Kabut 0-II, Kabut 0-III
     */
    public function scopeFindVisibility($query, $type)
    {
        return $query->where('visibility','like','%'.$type.'%');
    }

    /**
     * Find Visibility 
     * @var $type = Nihil, Tidak Teramati, Teramati
     */
    public function scopeFindVisualAsap($query, $type)
    {
        return $query->where('visual_asap','like','%'.$type.'%');
    }

    /**     
     *   Masing-masing Visual hanya dimiliki
     *   oleh 1 data VAR
     */
    public function var()
    {
        return $this->belongsTo('App\MagmaVars','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Visual bisa memiliki parameter asap
     *   jika memang teramati
     */
    public function asap()
    {
        return $this->hasOne('App\VarAsap');
    }

    /**     
     *   Masing-masing Visual bisa memiliki parameter letusan
     *   jika memang teramati
     */
    public function letusan()
    {
        return $this->hasOne('App\VarLetusan');
    }
}
