<?php

namespace App\Services\Cendana;

use App\v1\MagmaVarOptimize;
use App\v1\User;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MagmaVarService
{
    /**
     * Endpoint for Cendana API
     * https://cendana15.com/docs/api/magmavar
     */
    const URL = 'https://cendana15.com';

    /**
     * VAR from Cendana
     *
     * @var null|array
     */
    public $var = null;

    /**
     * VAR from MAGMA
     *
     * @var null|MagmaVarOptimize
     */
    public $magmaVar = null;

    /**
     * Guzzle HTTP Client
     *
     * @var GuzzleHttp\Client
     */
    public $client;

    /**
     * Store all seismic paramater
     *
     * @var array
     */
    public $gempas = [];

    /**
     * Initiate variable
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->magmaVar = $this->magmaVar();
    }

    /**
     * Get User based on NIP from Cendana
     *
     * @return User
     */
    protected function user(): User
    {
        return User::select('vg_nip', 'vg_nama')
            ->where('vg_nip', $this->var['observer'][0]['nip'])->first();
    }

    /**
     * Get previous VAR from MAGMA
     *
     * @param string|null $code
     * @return MagmaVarOptimize
     */
    protected function magmaVar(?string $code = 'MER'): MagmaVarOptimize
    {
        $date = now()->subDay()->format('Ymd');

        return Cache::tags(['cendana'])->remember("$code$date", 120, function () use ($code) {
            return MagmaVarOptimize::select(
                'ga_code', 'pre_status', 'cu_status',
                'var_noticenumber', 'var_source', 'area',
                'var_rekom', 'magma_var_rekomendasi_id')
                ->where('ga_code', $code)
                ->with('gunungapi')
                ->orderBy('var_noticenumber', 'desc')
                ->first();
        });
    }

    /**
     * Get URL API Endpoint from Cendana
     *
     * @return string
     */
    public function url(): string
    {
        $endpoint = self::URL."/api/public/v1/magmavar";

        return "$endpoint?{$this->query()}";
    }

    /**
     * Clear cache
     *
     * @param boolean $flush
     * @return self
     */
    public function flush(bool $flush = true): self
    {
        if ($flush) Cache::tags(['cendana'])->flush();

        return $this;
    }

    /**
     * Get periodic ID
     * https://cendana15.com/docs/api/magmavar
     *
     * @return integer
     */
    public function periodId(): int
    {
        $periodIds = collect([
            0 => ['00:00', '00:29'], // 24 Jam
            1 => ['06:00', '11:59'], // 00:00 - 06:00
            2 => ['12:00', '15:59'], // 06:00 - 12:00
            3 => ['18:00', '23:59'], // 12:00 - 18:00
            4 => ['00:30', '05:59'], // 18:00 - 24:00
        ]);

        $periodIds->transform(function ($periods, $id) {
            $start = Carbon::createFromTimeString($periods[0]);
            $end = Carbon::createFromTimeString($periods[1]);

            return now()->between($start, $end) ? $id : null;
        });

        return (int) $periodIds->filter()->first();
    }

    /**
     * Return query parameter
     *
     * @return string
     */
    public function query(): string
    {
        $periodId = $this->periodId();
        $date = ($periodId === 0 || $periodId === 4) ? now()->subDay()->format('Y-m-d') : now()->format('Y-m-d');

        return "date=$date&period=$periodId";
    }

    /**
     * Set $this->var variable
     *
     * @return self`
     */
    public function var(): self
    {
        $this->var = Cache::tags(['cendana'])->remember("cendana:magma-vars:{$this->query()}", 120, function () {
            $response = $this->client->get($this->url());
            return json_decode($response->getBody(), true);
        });

        return $this;
    }

    /**
     * Get Var Issued in d/m/Y H:m:s format
     *
     * @return string
     */
    protected function varIssued(): string
    {
        return Carbon::parse($this->var['createdAt'])->format('d/m/Y H:m:s');
    }

    /**
     * Get current status from Cendana
     *
     * @return string
     */
    protected function cuStatus(): string
    {
        $level = (int) $this->var['activityLevel']['level'];

        switch ($level) {
            case 1:
                return 'Level I (Normal)';
            case 2:
                return 'Level II (Waspada)';
            case 3:
                return 'Level III (Siaga)';
            case 4:
                return 'Level IV (Awas)';
        }
    }

    /**
     * Convert periode pemantauan from Cendana
     *
     * @return string
     */
    protected function periode(): string
    {
        switch ((int) $this->var['periodId']) {
            case 0:
                return '00:00 - 24:00';
            case 1:
                return '00:00 - 06:00';
            case 2:
                return '06:00 - 12:00';
            case 3:
                return '12:00 - 18:00';
            case 4:
                return '18:00 - 24:00';
        }
    }

    /**
     * Generate Noticenumber based on periode
     *
     * @return string
     */
    protected function varNoticenumber(): string
    {
        $period = '';

        switch ((int) $this->var['periodId']) {
            case 0:
                $period = '2400';
                break;
            case 1:
                $period = '0000';
                break;
            case 2:
                $period = '0600';
                break;
            case 3:
                $period = '1200';
                break;
            case 4:
                $period = '1800';
                break;
        }

        $date = Carbon::createFromFormat('Y-m-d', $this->var['observationDate'])->format('Ymd');

        return $date.$period;
    }

    /**
     * Formatiing taken at for MAGMA VAR
     *
     * @return string
     */
    protected function takenAt(): string
    {
        $timezone = $this->magmaVar->gunungapi->ga_zonearea;

        switch ($timezone) {
            case 'WIB':
                $plus = 'UTC +7';
                break;
            case 'WITA':
                $plus = 'UTC +8';
                break;
            default:
                $plus = 'UTC +9';
                break;
        }

        return "Taken {$this->var['photo']['takenAt']} $timezone ($plus)";
    }

    /**
     * Get volcano location based on previous VAR
     *
     * @return string
     */
    protected function volcanoLocation(): string
    {
        return "{$this->magmaVar->gunungapi->ga_lat_gapi}, {$this->magmaVar->gunungapi->ga_lon_gapi}";
    }

    /**
     * Get Periode of report, 24 Jam or 6 Jam
     *
     * @return string
     */
    protected function varPerwkt(): string
    {
        return ((int) $this->var['periodId'] === 0) ? '24 Jam' : '6 Jam';
    }

    /**
     * Get VAR Visibility
     *
     * @return string
     */
    protected function varVisibility(): string
    {
        $visibility = collect($this->var['visual']['visibility'])->map(function ($visibility) {
            if ($visibility === 'TAMPAK')
                return 'Jelas';
            if ($visibility === 'KABUT 01')
                return 'Kabut 0-I';
            if ($visibility === 'KABUT 02')
                return 'Kabut 0-II';
            if ($visibility === 'KABUT 03')
                return 'Kabut 0-III';
        })->sort()->toArray();

        return implode(', ', $visibility);
    }

    /**
     * Get weather condition from Cendana
     *
     * @return string
     */
    protected function varCuaca(): string
    {
        $cuaca = collect($this->var['meteorology']['weather'])->map(function ($cuaca) {
            return Str::title($cuaca);
        })->toArray();

        return implode(', ', $cuaca);
    }

    /**
     * Get rainfall parameter from Cendana
     *
     * @return float
     */
    protected function varCurahHujan(): float
    {
        if (is_null($this->var['meteorology']['rainfall']))
            return 0;

        return (float) $this->var['meteorology']['rainfall']['value'];
    }

    /**
     * Get smoke visibility
     *
     * @return string
     */
    protected function varAsap(): string
    {
        if (is_null($this->var['visual']['smoke']))
            return 'Nihil';

        return 'Teramati';
    }

    /**
     * Get height of smoke
     *
     * @return integer
     */
    protected function varTasapMin(): int
    {
        if (is_null($this->var['visual']['smoke']))
            return 0;

        return $this->var['visual']['smoke']['height'];
    }

    /**
     * Get smoke color
     *
     * @return string
     */
    protected function varWasap(): string
    {
        if (is_null($this->var['visual']['smoke']))
            return '';

        return Str::title($this->var['visual']['smoke']['color']);
    }

    /**
     * Get smoke intensity
     *
     * @return string
     */
    protected function varIntasap(): string
    {
        if (is_null($this->var['visual']['smoke']))
            return '';

        return Str::title($this->var['visual']['smoke']['intensity']);
    }

    /**
     * Get smoke pressure
     *
     * @return string
     */
    protected function varTekasap(): string
    {
        if (is_null($this->var['visual']['smoke']))
        return '';

        return Str::title($this->var['visual']['smoke']['pressure']);
    }

    /**
     * Get gempa with SP
     *
     * @param array $gempa
     * @param string $code
     * @return self
     */
    protected function gempaSp(array $gempa, string $code): self
    {
        $this->gempas["var_{$code}"] = $gempa['count'];
        $this->gempas["var_{$code}_amin"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['min'];
        $this->gempas["var_{$code}_amax"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['max'];
        $this->gempas["var_{$code}_dmin"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['min'];
        $this->gempas["var_{$code}_dmax"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['max'];
        $this->gempas["var_{$code}_spmin"] = 0;
        $this->gempas["var_{$code}_spmax"] = 0;

        return $this;
    }

    /**
     * Get gempa with normal attirbute
     *
     * @param array $gempa
     * @param string $code
     * @return self
     */
    protected function gempaNormal(array $gempa, string $code): self
    {
        $this->gempas["var_{$code}"] = $gempa['count'];
        $this->gempas["var_{$code}_amin"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['min'];
        $this->gempas["var_{$code}_amax"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['max'];
        $this->gempas["var_{$code}_dmin"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['min'];
        $this->gempas["var_{$code}_dmax"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['max'];

        return $this;
    }

    /**
     * Get gempa for dominan attributes
     *
     * @param array $gempa
     * @param string $code
     * @return self
     */
    protected function gempaDominan(array $gempa, string $code): self
    {
        $this->gempas["var_{$code}"] = $gempa['count'];
        $this->gempas["var_{$code}_amin"]  = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['min'];
        $this->gempas["var_{$code}_amax"]  = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['max'];
        $this->gempas["var_{$code}_adom"]  = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['max'];

        return $this;
    }

    /**
     * Get gempa for pyroclastic flow type
     *
     * @param array $gempa
     * @param string $code
     * @return self
     */
    protected function gempaLuncuran(array $gempa, string $code): self
    {
        $this->gempas["var_{$code}"] = $gempa['count'];
        $this->gempas["var_{$code}_amin"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['min'];
        $this->gempas["var_{$code}_amax"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['max'];
        $this->gempas["var_{$code}_dmin"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['min'];
        $this->gempas["var_{$code}_dmax"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['max'];
        $this->gempas["var_{$code}_rmin"] = 0;
        $this->gempas["var_{$code}_rmax"] = 0;
        $this->gempas["var_{$code}_alun"] = '';

        return $this;
    }

    /**
     * Get gempa for eruption type
     *
     * @param array $gempa
     * @param string $code
     * @return self
     */
    protected function gempaErupsi(array $gempa, string $code): self
    {
        $this->gempas["var_{$code}"] = $gempa['count'];
        $this->gempas["var_{$code}_amin"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['min'];
        $this->gempas["var_{$code}_amax"] = $gempa['count'] === 1 ? $gempa['amplitude'] : $gempa['amplitude']['max'];
        $this->gempas["var_{$code}_dmin"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['min'];
        $this->gempas["var_{$code}_dmax"] = $gempa['count'] === 1 ? $gempa['duration'] : $gempa['duration']['max'];
        $this->gempas["var_{$code}_tmin"] = 0;
        $this->gempas["var_{$code}_tmax"] = 0;
        $this->gempas["var_{$code}_wasap"] = '';

        return $this;
    }

    /**
     * Translate type of sesmicity
     *
     * @param array $gempa
     * @return self
     */
    protected function translateJenisGempa(array $gempa): self
    {
        $translate = [
            'VTA' => [
                'code' => 'vta',
                'type' => 'sp',
            ],
            'VTB' => [
                'code' => 'vtb',
                'type' => 'normal',
            ],
            'MP' => [
                'code' => 'hyb',
                'type' => 'normal',
            ],
            'LF' => [
                'code' => 'lof',
                'type' => 'normal',
            ],
            'ROCKFALL' => [
                'code' => 'gug',
                'type' => 'luncuran',
            ],
            'GASBURST' => [
                'code' => 'hbs',
                'type' => 'normal',
            ],
            'TECT' => [
                'code' => 'tej',
                'type' => 'sp',
            ],
            'TECLOC' => [
                'code' => 'tel',
                'type' => 'sp',
            ],
            'EXPLOSION' => [
                'code' => 'lts',
                'type' => 'erupsi',
            ],
            'AWANPANAS' => [
                'code' => 'apg',
                'type' => 'luncuran',
            ],
            'TREMOR' => [
                'code' => 'tre',
                'type' => 'dominan'
            ],
        ];

        $type = $translate[$gempa['code']]['type'];
        $code = $translate[$gempa['code']]['code'];

        switch ($type) {
            case 'sp':
                return $this->gempaSp($gempa, $code);
            case 'normal':
                return $this->gempaNormal($gempa, $code);
            case 'dominan':
                return $this->gempaDominan($gempa, $code);
            case 'luncuran':
                return $this->gempaLuncuran($gempa, $code);
            case 'erupsi':
                return $this->gempaErupsi($gempa, $code);
        }
    }

    /**
     * Get all gempa
     *
     * @return Collection
     */
    protected function gempas(): Collection
    {
        collect($this->var['seismicity'])->transform(function ($gempa) {
            return $this->translateJenisGempa($gempa);
        });

        return collect($this->gempas);
    }

    /**
     * Check for existsing attributes to save
     *
     * @return array
     */
    public function existAttributes(): array
    {
        return [
            'ga_code' => 'MER',
            'var_noticenumber' => $this->varNoticenumber(),
        ];
    }

    /**
     * Transform VAR Cendana to MAGMA
     *
     * @return Collection
     */
    public function transform(): Collection
    {
        if (is_null($this->var['photo']['url']))
            return collect([]);

        if (collect($this->var['observer'])->isEmpty())
            return collect([]);

        return collect([
            'var_image' => $this->var['photo']['url'],
            'var_image_create' => $this->takenAt(),
            'var_issued' => $this->varIssued(),
            'ga_nama_gapi' => $this->var['volcano']['name'],
            'ga_id_smithsonian' => '263250',
            'cu_status' => $this->cuStatus(),
            'pre_status' => $this->magmaVar->pre_status,
            'var_source' => $this->magmaVar->var_source,
            'volcano_location' => $this->volcanoLocation(),
            'area' => $this->magmaVar->area,
            'summit_elevation' => $this->magmaVar->gunungapi->ga_elev_gapi,
            'var_perwkt' => $this->varPerwkt(),
            'periode' => $this->periode(),
            'var_visibility' => $this->varVisibility(),
            'var_cuaca' => $this->varCuaca(),
            'var_curah_hujan' => $this->varCurahHujan(),
            'var_suhumin' => $this->var['meteorology']['temperature']['min'],
            'var_suhumax' => $this->var['meteorology']['temperature']['max'],
            'var_kelembabanmin' => $this->var['meteorology']['humidity']['min'],
            'var_kelembabanmax' => $this->var['meteorology']['humidity']['max'],
            'var_tekananmin' => $this->var['meteorology']['pressure']['min'],
            'var_tekananmax' => $this->var['meteorology']['pressure']['max'],
            'var_kecangin' => Str::title($this->var['meteorology']['windSpeed']['level']),
            'var_arangin' => Str::title($this->var['meteorology']['windDirection']),
            'var_asap' => $this->varAsap(),
            'var_tasap_min' => $this->varTasapMin(),
            'var_tasap' => $this->varTasapMin(),
            'var_wasap' => $this->varWasap(),
            'var_intasap' => $this->varIntasap(),
            'var_tekasap' => $this->varTekasap(),
            'var_viskawah' => 'Nihil',
            'var_ketlain' => 'Nihil',
            'var_rekom' => $this->magmaVar->var_rekom,
            'magma_var_rekomendasi_id' => $this->magmaVar->magma_var_rekomendasi_id,
            'var_nip_pelapor' => $this->var['observer'][0]['nip'],
            'var_nama_pelapor' => $this->user()->vg_nama,
            'var_data_date' => $this->var['observationDate'],
        ])->merge($this->gempas());
    }

    /**
     * Convert collection to array
     *
     * @return array
     */
    public function fill(): array
    {
        return $this->transform()->toArray();
    }

    /**
     * Get VAR from cendana
     *
     * @return void
     */
    public function get()
    {
        return $this->var;
    }

    /**
     * Store VAR to MAGMA v1
     *
     * @return MagmaVarOptimize|null
     */
    public function storeToOldMagmaVar(): ?MagmaVarOptimize
    {
        if (!is_null($this->var)) {
            $var = MagmaVarOptimize::updateOrCreate(
                $this->existAttributes(),
                $this->fill()
            );

            return $var;
        }

        return null;
    }
}