<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $guarded = ['id'];

    public function events()
    {
        return $this->hasMany('App\EventCatalog', 'code_event', 'code');
    }
}
