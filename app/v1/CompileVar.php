<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class CompileVar extends Model
{
    protected $connection = 'magma';

    protected $primaryKey = 'id';

    protected $table = 'compile_var';

    protected $fillable = [
        'ga_code',
        'start',
        'end',
        'is_active'
    ];

    protected $dates = [
        'start',
        'end'
    ];

    protected $casts = [
        'is_active',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }
}
