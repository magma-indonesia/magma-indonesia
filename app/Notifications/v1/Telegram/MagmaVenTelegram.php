<?php

namespace App\Notifications\v1\Telegram;

use App\Traits\v1\DeskripsiLetusan;
use App\v1\MagmaVen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class MagmaVenTelegram extends Notification
{
    use Queueable;
    use DeskripsiLetusan;

    protected $ven;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MagmaVen $ven)
    {
        $this->ven = $ven;
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
            ->content($this->deskripsiTelegram($this->ven));
    }
}
