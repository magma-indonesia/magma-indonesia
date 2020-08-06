<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Glossary extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    /**
     * Mengembalikan semua fungsi nama judul
     * dalam slug format
     *
     * @var string
     */
    public function setSlugAttribute($name)
    {
        $this->attributes['slug'] = str_slug($name);
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
        return $this->belongsTo('App\User','nip','nip');
    }

    /**
     * Has Many files
     *
     * @return \App\GlossaryFile
     */
    public function glossary_files()
    {
        return $this->hasMany('App\GlossaryFile');
    }
}
