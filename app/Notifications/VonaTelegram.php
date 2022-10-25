<?php

namespace App\Notifications;

use App\Traits\VonaTrait;
use App\Vona;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class VonaTelegram extends Notification implements ShouldQueue
{
    use Queueable;
    use VonaTrait;

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

    protected function deskripsiVonaTelegram()
    {
        $this->vona->load('gunungapi');

        $header = $this->vona->type === 'EXERCISE' ? "VA EXERCISE APAC VOLCEX 22/01" : "{$this->vona->gunungapi->name} {$this->vona->issued_utc}";

        $footer = $this->vona->type === 'EXERCISE' ? "VA EXERCISE VA EXERCISE VA EXERCISE" : $header;

        $text = "*{$header}*\n"
        . "====================\n\n"
        . "*ISSUED :*\n"
        . "{$this->vona->issued_utc}\n\n"
        . "*VOLCANO :*\n"
        . "{$this->vona->gunungapi->name} ({$this->vona->gunungapi->smithsonian_id})\n\n"
        . "*COLOR CODE :*\n"
        . "{$this->vona->current_code}\n\n"
        . "*VOLCANIC ACTIVITY SUMMARY :*\n"
        . "{$this->volcanoActivitySummary($this->vona)}\n\n"
        . "*VOLCANIC CLOUD HEIGHT :*\n"
        . "{$this->volcanicCloudHeight($this->vona)}\n\n"
        . "*REMARKS :*\n"
        . "{$this->remarks($this->vona)}\n\n"
        . "====================\n"
        . "*{$footer}*";

        return $text;
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
        $url = URL::signedRoute('v1.vona.show', ['id' => $this->vona->old_id]);

        return TelegramMessage::create()
            ->content($this->deskripsiVonaTelegram())
            ->button('View VONA', $url);
    }
}
