<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportGempa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:gempa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data kegempaan Gunung Api v1 ke v2';

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
            $controller = app()->make('App\Http\Controllers\Import\ImportGempa');
            app()->call([$controller, 'import']);
            return $this;
        }

        catch (Exception $e)
        {
            Log::channel('import')->error('[FAILED] Gagal Import Import VAR Kegempaan : '.now());
            Log::channel('import')->debug($e);
        }
    }
}
