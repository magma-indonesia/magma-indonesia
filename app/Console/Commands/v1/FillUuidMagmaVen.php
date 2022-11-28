<?php

namespace App\Console\Commands\v1;

use App\v1\MagmaVen;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillUuidMagmaVen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:uuid-magma-ven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi UUID MAGMA VEN';

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
        $this->info('Updating UUID Magma VEN...');

        $vens = MagmaVen::select('erupt_id','uuid')->whereNull('uuid')->get();
        $vens->each(function ($ven) {
            $ven->uuid = Str::uuid();
            $ven->save();
        });

        $this->info('Update UUID berhasil');
    }
}
