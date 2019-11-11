<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportFotoVisual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:foto_visual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download data foto visual Magma V1 ke V2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportFotoVisual');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Download Foto Visual berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Download Foto Visual GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Download Foto Visual : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
