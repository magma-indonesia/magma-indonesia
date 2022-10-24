<?php

namespace App\Jobs;

use App\Mail\VonaSend;
use App\v1\Vona as V1Vona;
use App\Vona;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendVonaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $vona;

    public $subscribers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Vona $vona, Collection $subcribers)
    {
        $this->vona = $vona;
        $this->subscribers = $subcribers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->subscribers->each(function ($sub) {
            Mail::to($sub->email)
                ->send(new VonaSend($this->vona));
        });

        $this->vona->update([
            'is_sent' => 1,
        ]);

        $oldVona = V1Vona::where('no', $this->vona->old_id)->first();
        $oldVona->update([
            'sent' => 1,
        ]);
    }
}
