<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class UserLogin extends Notification
{
    use Queueable;

    protected $type;

    protected $user;
    
    protected $opsi;

    protected function content()
    {
        switch ($this->type) {
            case 'api':
                return '*API - '.$this->user->name.'* berhasil login';
            case 'web':
                return '*'.$this->user->name.'* login via Web';
            case 'rsam':
                return '*'.$this->user->name.'* membuat RSAM *'.$this->opsi['channel'].'* periode *'.$this->opsi['periode'].'*';
            case 'evaluasi':
                return '*'.$this->user->name.'* membuat Evaluasi *'.$this->opsi['gunungapi'].'* periode *'.$this->opsi['periode'].'*';
            default:
                break;
        }
    }

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type = 'web', $user, $opsi = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->opsi = $opsi;
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
                    ->content($this->content());
    }
}
