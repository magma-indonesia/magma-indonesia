<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class GertanCrs extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'idx';

    protected $table = 'magma_crs';
    
    public function getCrsPrvAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsCtyAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsRgnAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsVilAttribute($value)
    {
        return title_case($value);
    }

    public function getCrsBwdAttribute($value)
    {
        return title_case($value);
    }

}
