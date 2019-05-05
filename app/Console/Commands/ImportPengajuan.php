<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportPengajuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pengajuan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data Pengajuan pegawai PVMBG';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportPengajuan');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Import Data Pengajuan berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Import Data Pengajuan GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Import Pengajuan : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
