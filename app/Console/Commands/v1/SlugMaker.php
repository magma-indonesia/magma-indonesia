<?php

namespace App\Console\Commands\v1;

use App\v1\Gadd;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SlugMaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug:maker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan informasi slug name pada database magma v1';

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
        $gadds = Gadd::all();
        $gadds->each(function ($gadd) {
            $gadd->slug = Str::slug($gadd->ga_nama_gapi);
            $gadd->save();
        });

        $this->info('Update slug berhasil');
    }
}
