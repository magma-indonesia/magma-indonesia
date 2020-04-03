<?php

namespace App\Console\Commands;

use App\Seismometer;
use App\LiveSeismogram as LiveSeismogramModel;
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

    protected function updateLiveSeismogram($seismometer, $filename = null)
    {
        if ($filename) {
            if ($live = LiveSeismogramModel::whereSeismometerId($seismometer->id)->first())
            {
                Storage::disk('seismogram')->delete($live->filename);
                Storage::disk('seismogram')->delete('thumb_'.$live->filename);
            }
        }

        LiveSeismogramModel::updateOrCreate(
            [
                'seismometer_id' => $seismometer->id
            ],[
                'code' => $seismometer->code,
                'filename' => $filename,
            ]
        );

        return $this;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Updating Live Seismogram....');

        $watermark = Image::make('public/watermark-seismogram.png');

        $seismometers = Seismometer::wherePublished(1)->get();
        if ($seismometers->isNotEmpty())
        {
            $seismometers->each(function ($seismometer) use($watermark) {

                try {
                    $image = Image::make($seismometer->full_url)
                                ->insert($watermark, 'bottom-right', 80, 2);
                    $filename = sha1(uniqid()).'.png';

                    if (Storage::disk('seismogram')->put($filename, $image->stream()))
                    {
                        Storage::disk('seismogram')->put('thumb_'.$filename, $image->widen(300)->stream());
                        $this->updateLiveSeismogram($seismometer, $filename);
                    }
                }
    
                catch (\Exception $e)
                {
                    $this->updateLiveSeismogram($seismometer);
                }
            });
        }

        $this->info('Seismogram berhasil diupdate');
    }
}
