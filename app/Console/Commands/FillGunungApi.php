<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillGunungApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuill:gunung-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill data Gunung Api';

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
