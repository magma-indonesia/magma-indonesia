<?php

namespace App\Console\Commands;

use App\DataDasarGeologi;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FillDataDasarGeologi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:data-dasar-geologi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi data dasar geologi gunung api';

    /**
     * Data Dasar Geologi
     *
     * @var Collection
     */
    protected $dataDasarGeologi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->dataDasarGeologi = $this->dataDasarGeologi();
    }

    protected function dataDasarGeologi(): Collection
    {
        $json = storage_path() . '/json/data-dasar-geologi.json';
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

        $this->dataDasarGeologi->each(function ($geologi) {
            DataDasarGeologi::updateOrCreate([
                'code' => $geologi->code,
            ],[
                'umum' => $geologi->umum,
                'morfologi' => $geologi->morfologi,
                'stratigrafi' => $geologi->stratigrafi,
                'struktur_geologi' => $geologi->struktur_geologi,
                'petrografi' => $geologi->petrografi,
                'nip' => '198803152015031005',
            ]);
        });

        $this->info('Selesai');
    }
}
