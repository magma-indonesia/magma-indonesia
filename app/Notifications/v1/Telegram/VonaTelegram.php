<?php

namespace App\Notifications\v1\Telegram;

use App\v1\Vona;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class VonaTelegram extends Notification
{
    use Queueable;

    protected $vona;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Vona $vona)
    {
        $this->vona = $vona;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->content('Awesome *bold* text and [inline URL](https://magma.esdm.go.id)');
    }
}
