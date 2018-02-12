<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class PressRelease extends Notification
{
    use Queueable;

    protected $type,$press;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type,$press)
    {
        $this->type = $type;        
        $this->press = $press;
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
        $url = url('/chambers/press/'.$this->press->id);

        if ($this->type == 1){
            return (new SlackMessage)
                        ->success()
                        ->from('Super Admin',':ghost:')
                        ->content('Press Release telah *dibuat*')
                        ->attachment(function ($attachment) use ($url){
                            $attachment->title($this->press->title,$url)
                                    ->fields([
                                        'Nama' => $this->press->user->name,
                                        'Via' => 'Web'
                                    ]);
                        });
        }

        if ($this->type == 3){
            return (new SlackMessage)
                        ->warning()
                        ->from('Super Admin',':ghost:')
                        ->content('Press Release telah *diubah*')
                        ->attachment(function ($attachment) use ($url){
                            $attachment->title($this->press->title,$url)
                                    ->fields([
                                        'Nama' => $this->press->user->name,
                                        'Via' => 'Web'
                                    ])
                                    ->timestamp($this->press->updated_at);
                        });
        }

        return (new SlackMessage)
                    ->error()
                    ->from('Super Admin',':ghost:')
                    ->content('Press Release berhasil *dihapus*')
                    ->attachment(function ($attachment) use ($url){
                        $attachment->title($this->press->title,$url)
                                   ->fields([
                                        'Nama' => $this->press->user->name,
                                        'Via' => 'Web'
                                   ]);
                    });

    }
}
