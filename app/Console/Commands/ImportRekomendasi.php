<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportRekomendasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:rekomendasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import history rekomendasi MAGMA-VAR';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 1200);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $controller = app()->make('App\Http\Controllers\Import\ImportVarRekomendasi');
            app()->call([$controller, 'import']);
            return $this;
        }

        catch (Exception $e)
        {
            Log::channel('import')->error('[FAILED] Gagal Import Rekomendasi Gunung Api : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
