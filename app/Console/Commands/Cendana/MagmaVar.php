<?php

namespace App\Console\Commands\Cendana;

use App\Services\Cendana\MagmaVarService;
use Illuminate\Console\Command;

class MagmaVar extends Command
{
    public $magmaVarService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cendana:magma-var';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching MAGMA-VAR from Cendana15';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->magmaVarService = new MagmaVarService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Importing VAR from Cendana');
        $this->magmaVarService->var()->storeToOldMagmaVar();
        $this->info('Imported!');
    }
}
