<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data Pengguna MAGMA v1 ke v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportUsers');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Import User MAGMA berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Import User MAGMA GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Import data User : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
