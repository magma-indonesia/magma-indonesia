<?php

namespace App\Console\Commands;

use App\DataDasarSejarahLetusan;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FillDataDasarSejarahLetusan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:data-dasar-sejarah-letusan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi data dasar sejarah letusan gunung api';

    /**
     * Data Dasar Gunung Api
     *
     * @var Collection
     */
    protected $dataDasarSejarahLetusanGunungApi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->dataDasarSejarahLetusanGunungApi = $this->dataDasarSejarahLetusanGunungApi();
    }

    /**
     * Get data dasar gunung api
     *
     * @return Collection
     */
    protected function dataDasarSejarahLetusanGunungApi(): Collection
    {
        $json = storage_path() . '/json/data-dasar-sejarah-letusan.json';
        return collect(json_decode(file_get_contents($json)));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->description);

        if (DataDasarSejarahLetusan::count() === 0) {
            $this->dataDasarSejarahLetusanGunungApi->each(function ($sejarah) {
                DataDasarSejarahLetusan::create([
                    'code' => $sejarah->code,
                    'start_year' => $sejarah->start_year,
                    'start_month' => $sejarah->start_month ?? null,
                    'start_date' => $sejarah->start_date ?? null,
                    'end_year' => $sejarah->end_year ?? $sejarah->start_year,
                    'end_month' => $sejarah->end_month ?? null,
                    'end_date' => $sejarah->end_date ?? null,
                    'description' => $sejarah->description ?? 'Tidak ada keterangan.',
                    'is_checked' => $sejarah->checked ?? 0,
                    'nip' => '198510082014022001',
                ]);
            });
        }

        $this->info('Selesai');
    }
}
