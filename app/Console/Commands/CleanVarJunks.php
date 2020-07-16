<?php

namespace App\Console\Commands;

use App\v1\MagmaVar;
use Illuminate\Console\Command;

class CleanVarJunks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:junks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan data sampah di MAGMA v1';

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
        $this->info('Deleting Junk Data....');

        $junkVars = MagmaVar::where('var_nama_pelapor','=','')->get();
        $this->info('Deleting Junk Vars');

        $bar = $this->output->createProgressBar($junkVars->count());
        $bar->start();

        foreach ($junkVars as $junkVar) {
            $junkVar->delete();
            $bar->advance();
        }

        $bar->finish();
        $this->info(' Junk VAR Deleted');

    }
}
