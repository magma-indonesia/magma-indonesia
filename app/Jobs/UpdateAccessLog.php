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

    private $ip;

    private $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ip, $url)
    {
        $this->ip = $ip;
        $this->url = $url;
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
            'date' => now()->format('Y-m-d'),
            'url' => $this->url,
        ]);
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
