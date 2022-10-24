<?php

namespace App\Console\Commands;

use App\Gadd as AppGadd;
use App\v1\Gadd;
use Illuminate\Console\Command;

class FillEnglishProvinceGadd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:province-en';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi daftar provinsi dalam bahasa inggris';

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
        $gaddOlds = Gadd::all();
        $gadds = AppGadd::all();

        $gadds->each(function($gadd) use ($gaddOlds) {
            $gaddOld = $gaddOlds->where('ga_code', $gadd->code)->first();
            $gadd->update([
                'province_en' => $gaddOld->ga_prov_gapi_en
            ]);
        });
    }
}
