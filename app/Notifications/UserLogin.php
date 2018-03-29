<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class UserLogin extends Notification
{
    use Queueable;

    protected $type,$user;

    protected function content()
    {
        $this->type == 'api' ? $content = '*API - '.$this->user->name.'* berhasil login' : $content = '*'.$this->user->name.'* login via Web';

        return $content;
    }

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type = 'web', $user)
    {
        $this->user = $user;
        $this->type = $type;
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
        return (new SlackMessage)
                    ->from('Super Admin',':ghost:')
                    ->content('*'.$this->content().'* login via Web');
    }
}
