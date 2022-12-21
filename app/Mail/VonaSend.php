<?php

namespace App\Mail;

use App\Traits\VonaTrait;
use App\v1\MagmaVen;
use App\Vona;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VonaSend extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    use VonaTrait;

    public $vona;
    public $location;
    public $volcano_activity_summary;
    public $volcanic_cloud_height;
    public $other_volcanic_cloud_information;
    public $remarks;
    public $ven;

    /**
     * get VONA model
     *
     * @param Vona $vona
     */
    public function __construct(Vona $vona)
    {
        $this->vona = $vona;
        $this->location = $this->location($vona);
        $this->volcano_activity_summary = $this->volcanoActivitySummary($vona);
        $this->volcanic_cloud_height = $this->volcanicCloudHeight($vona);
        $this->other_volcanic_cloud_information = $this->otherVolcanicCloudInformation($vona);
        $this->remarks = $this->remarks($vona);
        $this->ven = $vona->old_ven_uuid ?
            MagmaVen::where('uuid', $vona->old_ven_uuid)->first() :
            null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = strtoupper($this->vona->gunungapi->name).' '. $this->vona->issued_utc;

        return $this->subject("VONA {$subject}")
                    ->view('emails.vona.send');
    }
}
