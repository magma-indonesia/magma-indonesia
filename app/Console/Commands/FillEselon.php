<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillEselon extends Command
{

    /**
     * Eselon collection from json file
     *
     * @var Collection
     */
    protected $eselons;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:eselon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Eselon table';

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
