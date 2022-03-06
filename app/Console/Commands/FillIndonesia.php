<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillIndonesia extends Command
{

    /**
     * Provinces collection from json file
     *
     * @var Collection
     */
    protected $provinces;

    /**
     * Cities collection from json file
     *
     * @var Collection
     */
    protected $cities;

    /**
     * Districts collection from json file
     *
     * @var Collection
     */
    protected $districts;

    /**
     * Villages collection from json file
     *
     * @var Collection
     */
    protected $villages;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:indonesia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Province, City, District, and Village';

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
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
