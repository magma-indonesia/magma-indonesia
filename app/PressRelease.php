<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\PressReleaseFile;

class PressRelease extends Model
{
    use Notifiable;

    protected $casts = [
        'gunung_api' => 'boolean',
        'gerakan_tanah' => 'boolean',
        'gempa_bumi' => 'boolean',
        'tsunami' => 'boolean',
    ];

    protected $guarded = [
        'id',
    ];

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return "-1001228642046";
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd', 'code_id', 'code');
    }

    public function pengirim()
    {
        return $this->belongsTo('App\User', 'nip_pengirim', 'nip');
    }

    /**
     * Has Many files
     *
     * @return \App\PressReleaseFile
     */
    public function press_release_files()
    {
        return $this->hasMany(PressReleaseFile::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
