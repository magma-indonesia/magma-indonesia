<?php

namespace App\Mail\v1;

use App\v1\Vona;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VonaSend extends Mailable
{
    use Queueable, SerializesModels;

    public $vona;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Vona $vona)
    {
        $this->vona = $vona;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('VONA Sinabung 20210322/0913Z')
            ->view('emails.vona.send');
    }
}
