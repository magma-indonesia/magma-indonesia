<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportMagmaVen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data informasi Letusan Gunung Api (VEN) v1 ke v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportMagmaVen');
            app()->call([$controller, 'import']);
            return $this;
        }

        catch (Exception $e)
        {
            Log::channel('import')->error('[FAILED] Gagal Import Magma VEN : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
