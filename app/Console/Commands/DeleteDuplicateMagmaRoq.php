<?php

namespace App\Console\Commands;

use App\v1\MagmaRoq;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDuplicateMagmaRoq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gempa:delete-duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete duplikasi data gempa';

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
        $this->info('Menghapus duplikasi gempa BMKG...');

        $magmaRoqsLatestGempa = MagmaRoq::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT * FROM magma_roq ORDER BY datetime_wib DESC) t'))
            ->groupBy('t.id_lap')
            ->pluck('no');

        $roqsWillBeDeleted = MagmaRoq::whereNotIn('no', $magmaRoqsLatestGempa)
            ->delete();

        $this->info('Deleting duplikasi gempa BMKG Berhasil!');
    }
}
