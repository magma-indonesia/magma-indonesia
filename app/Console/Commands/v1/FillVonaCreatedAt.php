<?php

namespace App\Console\Commands\v1;

use App\v1\MagmaVen;
use Illuminate\Console\Command;

class FillVonaCreatedAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:vona-created-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi kolom vona_created_at untuk VEN';

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
        $this->info('Filling vona_created_at for Magma VEN...');

        $vens = MagmaVen::select('erupt_id', 'vona_created_at')->whereNull('vona_created_at')->get();

        $vens->each(function ($ven) {
            $ven->vona_created_at = now();
            $ven->save();
        });

        $this->info('vona_created_at has been filled');
    }
}
