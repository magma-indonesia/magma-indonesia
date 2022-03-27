<?php

namespace App\Notifications\v1\Telegram;

use App\Traits\v1\DeskripsiLetusan;
use App\v1\MagmaVen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

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
        return TelegramFile::create()
            ->content($this->deskripsi($this->ven))
            ->photo($this->ven->erupt_pht)
            ->button('Lihat Laporan', $this->url($this->ven));
    }
}
