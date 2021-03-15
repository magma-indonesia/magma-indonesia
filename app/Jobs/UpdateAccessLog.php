<?php

namespace App\Jobs;

use App\StatistikAccess;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class UpdateAccessLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    private $ip;

    private $ips;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ip, $ips)
    {
        $this->ip = $ip;
        $this->ips = $ips;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        StatistikAccess::firstOrCreate([
                'ip_address' => $this->ip,
                'ips' => $this->ips,
            ])->increment('hit');
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::info('[FAILED] Accesses Log failed : ' . $this->ip);
    }
}
