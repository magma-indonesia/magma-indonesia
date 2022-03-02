<?php

namespace App\Jobs;

use App\StatistikLaporanHarian;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateLaporanHarianStatistik implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        StatistikLaporanHarian::firstOrCreate(['date' => now()->format('Y-m-d')])->increment('hit');
    }
}
