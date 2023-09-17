<?php

namespace App\Console\Commands;

use App\v1\Gadd;
use Illuminate\Console\Command;

class FillCodeToOldGadd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:code-to-old-gadd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi field code di ga_dd versi 1';

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
        $this->info($this->description);

        Gadd::all()->each(function ($gadd) {
            $gadd->code = $gadd->ga_code;
            $gadd->save();
        });

        $this->info('Selesai');
    }
}
