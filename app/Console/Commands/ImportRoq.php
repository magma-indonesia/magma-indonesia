<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportRoq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:roqs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data MAGMA-ROQ';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportRoq');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Import MAGMA-ROQ berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Import MAGMA-ROQ GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Import data MAGMA-ROQ: '.now());
            Log::channel('import')->debug($e);
        }
    }
}
