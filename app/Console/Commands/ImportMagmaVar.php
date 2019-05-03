<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportMagmaVar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data MAGMA-VAR v1 ke v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportMagmaVar');
            app()->call([$controller, 'import']);
            return $this;
        }

        catch (Exception $e)
        {
            Log::channel('import')->error('[FAILED] Gagal Import Import MagmaVar : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
