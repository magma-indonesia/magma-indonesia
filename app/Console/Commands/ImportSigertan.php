<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ImportSigertan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sigertan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data Gerakan Tanah';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportSigertan');
            app()->call([$controller, 'import']);
            return $this;
        }

        catch (Exception $e)
        {
            Log::channel('import')->error('[FAILED] Gagal Import data Gerakan Tanah: '.now());
            Log::channel('import')->debug($e);
        }
    }
}
