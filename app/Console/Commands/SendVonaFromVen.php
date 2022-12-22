<?php

namespace App\Console\Commands;

use App\v1\MagmaVen;
use Illuminate\Console\Command;

class SendVonaFromVen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:vona-from-ven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim VONA dari VEN';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vens = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')
            ->whereNull('vona_created_at')->get();

        $vens->each(function ($ven) {

        });
    }
}
