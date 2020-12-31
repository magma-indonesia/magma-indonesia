<?php

namespace App\Jobs;

use App\StatistikGallery;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateGalleryStatistik implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $var_no;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($var_no)
    {
        $this->var_no = $var_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        StatistikGallery::firstOrCreate(['no' => $this->var_no])->increment('hit');
    }
}
