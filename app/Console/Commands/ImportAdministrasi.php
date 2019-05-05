<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportAdministrasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:administrasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data Administrasi Pengguna MAGMA v1 ke v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportAdministratif');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Import Data Administrasi berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Import Data Administrasi GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Import Administrasi User : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
