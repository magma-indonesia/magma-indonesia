<?php

namespace App\Console\Commands;

use App\Seismometer;
use Illuminate\Console\Command;
use Image;
use Storage;

class LiveSeismogram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:live_seismogram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Live Seismogram';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Updating Live Seismogram....');

        $watermark = Image::make(asset('watermark-seismogram.png'));

        $seismograms = Seismometer::where('published',1)->get();
        $seismograms->each(function ($item, $value) use($watermark) {
            try {
                $image = Image::make($item->full_url);
                $image->insert($watermark, 'bottom-right', 80, 2);
                Storage::disk('seismogram')->put(uniqid(10).'.png', $image->stream());
            } 

            catch (\Exception $e)
            {
                $image = null;
            }
        });

        $this->info('Seismogram berhasil diupdate');
    }
}
