<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;
use Carbon\Carbon;
use App\v1\MagmaRoq;
use App\Import as ImportApp;
use App\Notifications\ImportNotification;
use Log;

class AutoGempaBmkg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gempa:bmkg';

    protected $id_laporan;
    protected $has_laporan = true;
    protected $datetime;
    protected $datetime_wib;
    protected $datetime_utc;

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
        try {
            $xml_en = XmlParser::load('http://data.bmkg.go.id/en_autogempa.xml');
            $gempa_en = $xml_en->parse([
                'tanggal' => ['uses' => 'gempa.Tanggal'],
                'jam' => ['uses' => 'gempa.Jam'],
                'koordinat' => ['uses' => 'gempa.point.coordinates'],
                'lintang' => ['uses' => 'gempa.Lintang'],
                'bujur' => ['uses' => 'gempa.Bujur'],       
            ]);
    
            $xml_id = XmlParser::load('http://data.bmkg.go.id/autogempa.xml');
            $gempa_id = $xml_id->parse([
                'kedalaman' => ['uses' => 'gempa.Kedalaman'],
                'magnitude' => ['uses' => 'gempa.Magnitude'],
                'wilayah_1'=> ['uses' => 'gempa.Wilayah1'],
                'wilayah_2' => ['uses' => 'gempa.Wilayah2'],
                'wilayah_3' => ['uses' => 'gempa.Wilayah3'],
                'wilayah_4' => ['uses' => 'gempa.Wilayah4'],
                'wilayah_5' => ['uses' => 'gempa.Wilayah5'],         
            ]);
    
            $this->gempa = collect($gempa_en+$gempa_id);

            return $this;

        } catch (Exception $e) {
            Log::info('[FAILED] Gagal Download data Gempa BMKG : '.now());
        }
    }

    /**
     * Get $gempa attribute
     *
     * @return void
     */
    protected function getGempa()
    {
        return $this->gempa;
    }

    protected function getMagnitude()
    {
        return explode(' ',$this->gempa['magnitude'])[0];
    }

    protected function getDepth()
    {
        return explode(' ',$this->gempa['kedalaman'])[0];
    }

    protected function getLatitude()
    {
        $latitude = explode(' ',$this->gempa['lintang']);
        $latitude = $latitude[1] == 'S' 
                    ? strval(0-$latitude[0])
                    : $latitude[0];

        return $latitude;
    }

    protected function getLongitude()
    {
        $longitude = explode(' ',$this->gempa['bujur'])[0];
        return $longitude;
    }

    protected function getArea()
    {
        return $this->gempa['wilayah_1'];
    }

    protected function getKoter()
    {
        $koter = explode(' ',$this->getArea())[3];
        return $koter;
    }

    protected function formatData()
    {
        $gempa = $this->getGempa();
        $gempa->transform(function ($item, $key) {
            return [
                'datetime_wib' => $this->getDateTimeWib(),
                'datetime_utc' => $this->getDateTimeUtc(),
                'id_lap' => $this->getIdLaporan(),
                'datetime_wib_str' => $this->getDateTimeWib(),
                'magnitude' => $this->getMagnitude(),
                'magtype' => 'SR',
                'depth' => $this->getDepth(),
                'dep_unit' => 'Km',
                'lat_lima' => $this->getLatitude(),
                'lon_lima' => $this->getLongitude(),
                'latlon_text' => $this->getLatitude().' LU '.$this->getLongitude().' BT',
                'area' => $this->getArea()?: null,
                'koter' => $this->getKoter(),
                'roq_source' => 'Badan Meteorologi, Klimatologi dan Geofisika (BMKG)'
            ];
        });

        $this->gempa = $gempa;
        return $this;
    }

    protected function saveGempa()
    {
        $gempa = $this->gempa->first();
        $roq = new MagmaRoq;
        $roq->fill($gempa);
        if ($roq->save())
        {
            $import = new ImportApp();
            $import->notify(new ImportNotification('Import Gempa BMKG >> '.$gempa['area']));
        }

        return $this;
    }

    protected function setIdLaporan()
    {
        $this->id_laporan = 'ROQ'.$this->datetime->format('YmdHis');
        return $this;
    }

    protected function getIdLaporan()
    {
        return $this->id_laporan;
    }

    protected function setDateTime()
    {
        $tanggal = $this->gempa['tanggal'];
        $waktu = explode(' ',$this->gempa['jam']);

        $datetime = Carbon::createFromFormat('d-M-y H:i:s',$tanggal.$waktu[0]);

        $this->datetime = $datetime;
        $this->setIdLaporan();

        $datetime_wib = $datetime->format('Y-m-d H:i:s');

        if ($waktu[1] == 'WITA') {
            $datetime_wib = $datetime->addHour()->format('Y-m-d H:i:s');
        };

        if ($waktu[1] == 'WIT') {
            $datetime_wib = $datetime->addHours(2)->format('Y-m-d H:i:s');
        };

        $this->datetime_wib = $datetime_wib;
        $this->datetime_utc = $datetime->subHours(7)->format('Y-m-d H:i:s');

        return $this;
    }

    protected function getDateTimeWib()
    {
        return $this->datetime_wib;
    }

    protected function getDateTimeUtc()
    {
        return $this->datetime_utc;
    }

    protected function createGempa()
    {
        $this->has_laporan ?: $this->formatData()->saveGempa();
        return $this;
    }

    protected function checkLaporanExists()
    {
        $id_laporan = $this->getIdLaporan();

        $roq = MagmaRoq::where('id_lap',$id_laporan)->first();

        $this->has_laporan = $roq ? true : false;

        return $this;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sedang melakukan importing gempa BMKG...');
        $this->setDateTime()
            ->checkLaporanExists()
            ->createGempa();
    }
}
