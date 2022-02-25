<?php

namespace App\Console\Commands\v1;

use Illuminate\Console\Command;
use App\v1\MagmaVarRekomendasi;
use App\v1\MagmaVarListRekomendasi;
use Illuminate\Support\Collection;

class FillRekomendasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:rekomendasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi daftar rekomendasi gunung api';

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
        $json = storage_path() . '/json/rekomendasi.json';
        $rekomendasis = json_decode(file_get_contents($json));
        $grouped = collect($rekomendasis)->groupBy([
            'code',
            'status',
            'date'
        ]);

        $grouped->each(function ($ga_codes, $key_ga_code) {
            $ga_codes->each(function ($statuses, $key_status) use ($key_ga_code) {
                $statuses->each(function ($dates, $key_date) use ($key_ga_code, $key_status) {
                    $rek = MagmaVarRekomendasi::firstOrCreate([
                        'ga_code' => $key_ga_code,
                        'status' => $key_status,
                        'date' => $key_date,
                        'is_active' => 1,
                    ]);

                    if (!$rek->lists()->exists()) {
                        $dates->each(function ($rekomendasi) use ($rek) {
                            $rek->lists()->create([
                                'rekomendasi' => $rekomendasi->rekomendasi,
                                'is_active' => $rekomendasi->is_active,
                            ]);
                        });
                    }
                });
            });
        });

        $this->info('Update Rekomendasi berhasil');
    }
}
