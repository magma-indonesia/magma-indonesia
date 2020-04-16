<?php

namespace App\Console\Commands\v1;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class CompileVar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compile:var';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kompilasi Laporan harian';

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
        try {
            $this->info('['.now().'] Sedang Compile MAGMA-VAR');
            $controller = app()->make('App\Http\Controllers\v1\CompileVarController');
            app()->call([$controller, 'compile']);
            $this->info('['.now().'] Compile MAGMA-VAR berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Compile MAGMA-VAR GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Compile MagmaVar : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
