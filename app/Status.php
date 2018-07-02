<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $casts = [
        'start_date' => 'datetime:Y-m-d H:i:s',
        'end_date' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id',
        'status',
        'start_date',
        'end_date'
    ];

    protected $appends = [
        'status_deskripsi'
    ];

    public function getStatusDeskripsiAttribute()
    {
        switch ($this->attributes['status']) {
            case '4':
                return 'Level IV (Awas)';
            case '3':
                return 'Level III (Siaga)';
            case '2':
                return 'Level II (Waspada)';
            default:
                return 'Level I (Normal)';
        }
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }
}
