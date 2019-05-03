<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCrs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:crs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data CRS (Laporan Masyarakat)';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportCrs');
            app()->call([$controller, 'import']);
            return $this;
        }

        catch (Exception $e)
        {
            Log::channel('import')->error('[FAILED] Gagal Import data CRS (Laporan Masyarakat): '.now());
            Log::channel('import')->debug($e);
        }
    }
}
