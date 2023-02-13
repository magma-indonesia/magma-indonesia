<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

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
        return config('services.telegram-bot-api.developer_channel');;
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd', 'code_id', 'code');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'nip', 'nip');
    }

    /**
     * Has Many files
     *
     * @return \App\PressReleaseFile
     */
    public function press_release_files()
    {
        return $this->hasMany('App\PressReleaseFile');
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
