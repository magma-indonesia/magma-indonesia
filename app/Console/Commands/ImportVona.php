<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportVona extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vona';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import VONA v1 ke v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportVona');
            app()->call([$controller, 'import']);
            $this->info('['.now().'] Import VONA berhasil');
            return $this;
        }

        catch (Exception $e)
        {
            $this->error('['.now().'] Import VONA GAGAL');
            Log::channel('import')->error('[FAILED] Gagal Import VONA : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
