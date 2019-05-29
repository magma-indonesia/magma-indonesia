<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportVarHarian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vardaily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data MAGMA-VAR harian v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportVarHarian');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Import MAGMA-VAR Harian berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Import MAGMA-VAR Harian GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Import Import MagmaVar Harian: '.now());
            Log::channel('import')->debug($e);
        }
    }
}
