<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\v1\MagmaRoq;
use App\Traits\v1\GunungApiTerdekat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AutoGempaBmkg extends Command
{

    use GunungApiTerdekat;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gempa:bmkg';

    protected $urlGempaTerkini = 'https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json';
    protected $urlGempaDirasakan = 'https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatisasi Import data Gempa BMKG';

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
     * Fetching Gempa Terkini skala >= 5
     *
     * @return Collection
     */
    protected function gempaTerkini(): Collection
    {
        $http = new Client;
        $response = $http->get($this->urlGempaTerkini);

        return collect(json_decode((string) $response->getBody(), true)['Infogempa']['gempa']);
    }

    /**
     * Fetching Gempa Dirasakan
     *
     * @return Collection
     */
    protected function gempaDirasakan(): Collection
    {
        $http = new Client;
        $response = $http->get($this->urlGempaDirasakan);

        return collect(json_decode((string) $response->getBody(), true)['Infogempa']['gempa']);
    }

    /**
     * Store ROQ
     *
     * @param array $gempa
     * @return void
     */
    protected function storeRoq(array $gempa): void
    {
        MagmaRoq::create($gempa);
    }

    /**
     * Convert UTC to WIB
     *
     * @param string $utc
     * @return string
     */
    protected function datetimeWib(string $utc): string
    {
        return Carbon::parse($utc)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    /**
     * Generate ID Laporan
     *
     * @param string $utc
     * @return string
     */
    protected function idLaporan(string $utc): string
    {
        $datetime = Carbon::parse($utc)->setTimezone('Asia/Jakarta')->format('YmdHis');
        return "ROQ$datetime";
    }

    /**
     * Get Latitude
     *
     * @param string $coordinates
     * @return string
     */
    protected function latitude(string $coordinates): string
    {
        return explode(',', $coordinates)[0];
    }

    /**
     * Get Longitude
     *
     * @param string $coordinates
     * @return string
     */
    protected function longitude(string $coordinates): string
    {
        return explode(',', $coordinates)[1];
    }

    /**
     * Transform data gempa
     *
     * @param Collection $gempa
     * @return Collection
     */
    protected function transformGempa(Collection $gempa): Collection
    {
        $gempa->transform(function ($gempa) {
            $wilayah = explode(' ', $gempa['Wilayah']);
            return [
                'datetime_wib' => $this->datetimeWib($gempa['DateTime']),
                'datetime_utc' => Carbon::parse($gempa['DateTime'])->format('Y-m-d H:i:s'),
                'id_lap' => $this->idLaporan($gempa['DateTime']),
                'datetime_wib_str' => $this->datetimeWib($gempa['DateTime']),
                'magnitude' => $gempa['Magnitude'],
                'magtype' => 'SR',
                'depth' => explode(' ', $gempa['Kedalaman'])[0],
                'dep_unit' => 'Km',
                'lat_lima' => $this->latitude($gempa['Coordinates']),
                'lon_lima' => $this->longitude($gempa['Coordinates']),
                'latlon_text' => $gempa['Lintang'] . ' ' . $gempa['Bujur'],
                'area' => $gempa['Wilayah'],
                'koter' => isset($gempa['Dirasakan']) ? $gempa['Wilayah'] : end($wilayah),
                'nearest_volcano' => $this->getGunungApiTerdekat(
                    $this->latitude($gempa['Coordinates']),
                    $this->longitude($gempa['Coordinates'])
                ),
                'mmi' => isset($gempa['Dirasakan']) ? $gempa['Dirasakan'] : null,
                'roq_source' => 'Badan Meteorologi, Klimatologi dan Geofisika (BMKG)'
            ];
        });

        return $gempa;
    }

    /**
     * Filter Data gempa
     *
     * @param Collection $gempas
     * @param Collection $roqs
     * @return Collection
     */
    protected function filtered(Collection $gempas, Collection $roqs): Collection
    {
        $id_laporans = $roqs->pluck('id_lap')->all();

        return $gempas->whereNotIn('id_lap', $id_laporans);
    }

    /**
     * Remove extsited gempa in database
     *
     * @param Collection $gempas
     * @return Collection
     */
    protected function getNonExistedRoq(Collection $gempas): Collection
    {
        $roqs = MagmaRoq::select('id_lap')->whereIn(
            'id_lap',
            $gempas->pluck('id_lap')->all()
        )->limit(30)->get();

        return $roqs->isEmpty() ? $gempas : $this->filtered($gempas, $roqs);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sedang melakukan importing gempa BMKG...');
        $gempas = $this->transformGempa(
            $this->gempaDirasakan()->merge($this->gempaTerkini())
        );

        $this->getNonExistedRoq($gempas)->each(function ($gempa) {
            $this->storeRoq($gempa);
        });
        $this->info('Importing gempa BMKG Berhasil!');
    }
}
