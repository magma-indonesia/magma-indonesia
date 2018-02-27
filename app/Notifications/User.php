<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class User extends Notification
{
    use Queueable;

    protected $type,$user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type,$user)
    {
        $this->type = $type;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        switch ($this->type) {
            case 'create':
                $note = 'ditambahkan';
                break;
            case 'update':
                $note = 'dirubah';
                break;
            default:
                $note = 'dihapus';
                break;
        }
        return (new SlackMessage)
                    ->from('Super Admin',':ghost:')
                    ->content('User *'.$this->user->name.'* berhasil *'.$note.'* - oleh _'.auth()->user()->name.'_');
    }
}
