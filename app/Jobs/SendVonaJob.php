<?php

namespace App\Jobs;

use App\Mail\VonaSend;
use App\v1\Vona;
use App\VonaSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendVonaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $vona;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Vona $vona)
    {
        $this->vona = $vona;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subsribers = VonaSubscriber::whereStatus(1)->get();

        $subsribers->each(function ($subsriber) {
            Mail::to($subsriber->email)
                ->send(new VonaSend($this->vona));
        });
    }
}
