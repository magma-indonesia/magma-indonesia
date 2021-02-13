<?php

namespace App\Jobs;

use App\Blacklist;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBlacklistLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Blacklist::firstOrCreate(['ip_address' => $this->ip])->increment('hit');
    }
}
