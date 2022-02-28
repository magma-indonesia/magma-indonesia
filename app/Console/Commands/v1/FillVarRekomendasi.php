<?php

namespace App\Console\Commands\v1;

use App\v1\MagmaVar;
use App\v1\MagmaVarRekomendasi;
use Illuminate\Console\Command;

class FillVarRekomendasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:var-rekomendasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill rekomendasi ID';

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
        $rekomendasis = MagmaVarRekomendasi::all();

        $rekomendasis->each(function ($rekomendasi) {

            switch ($rekomendasi->status) {
                case '1':
                    $status = 'Level I (Normal)';
                    break;
                case '2':
                    $status = 'Level II (Waspada)';
                    break;
                case '3':
                    $status = 'Level III (Siaga)';
                    break;
                default:
                    $status = 'Level IV (Awas)';
                    break;
            }

            MagmaVar::where('ga_code', $rekomendasi->ga_code)
                ->where('cu_status', $status)
                ->whereNull('magma_var_rekomendasi_id')
                ->update([
                    'magma_var_rekomendasi_id' => $rekomendasi->id
                ]);
        });

        $this->info('Update Rekomendasi ID magma_var berhasil');
    }
}
