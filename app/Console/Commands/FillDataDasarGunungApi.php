<?php

namespace App\Console\Commands;

use App\DataDasarGunungApi;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FillDataDasarGunungApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:data-dasar-gunung-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi data dasar gunung api';

    /**
     * Data Dasar Gunung Api
     *
     * @var Collection
     */
    protected $dataDasarGunungApi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->dataDasarGunungApi = $this->dataDasarGunungApi();
    }

    /**
     * Get data dasar gunung api
     *
     * @return Collection
     */
    protected function dataDasarGunungApi(): Collection
    {
        $json = storage_path() . '/json/data-dasar-gunung-api.json';
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

        $this->dataDasarGunungApi->each(function ($gunungApi) {
            DataDasarGunungApi::updateOrCreate([
                'code' => $gunungApi->code,
            ], [
                'pendahuluan' => $gunungApi->pendahuluan,
                'cara_mencapai_puncak' => $gunungApi->cara_mencapai_puncak,
                'nip' => '198803152015031005',
            ]);
        });

        $this->info('Selesai');
    }
}
