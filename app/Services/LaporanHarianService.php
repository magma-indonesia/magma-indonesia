<?php

namespace App\Services;

use App\v1\Gadd;
use App\v1\MagmaVar;
use App\v1\MagmaVarRekomendasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LaporanHarianService
{
    public $isCached = null;

    public $date = null;

    public function status(MagmaVar $var): string
    {
        switch ($var->cu_status) {
            case '1':
                return 'Level I (Normal)';
            case '2':
                return 'Level II (Waspada)';
            case '3':
                return 'Level III (Siaga)';
            default:
                return 'Level IV (Awas)';
        }
    }

    public function visibility(MagmaVar $var): string
    {
        if ($var->var_visibility->count() == 1 && in_array('Jelas', $var->var_visibility->toArray())) {
            return "Gunung api terlihat jelas. ";
        }

        if (in_array('Jelas', $var->var_visibility->toArray())) {
            return "Gunung api terlihat jelas hingga tertutup kabut. ";
        }

        return "Gunung api tertutup kabut. ";
    }

    public function warnaAsap(array $warnaAsap): string
    {
        return Str::replaceLast(
            ', ',
            ' dan ',
            strtolower(implode(', ', $warnaAsap))
        );
    }

    public function intensitasAsap(array $intensitasAsap): string
    {
        return Str::replaceLast(
            ', ',
            ' hingga ',
            strtolower(implode(', ', $intensitasAsap))
        );
    }

    public function tinggiAsap(int $tinggiMinimum, int $tinggiMaksimum): string
    {
        $collection = collect([
            $tinggiMinimum,
            $tinggiMaksimum
        ])->reject(function ($tinggi) {
            return $tinggi == 0;
        })->unique();

        if ($collection->isEmpty()) {
            return 'tinggi asap tidak teramati. ';
        }

        if ($collection->count() == 1) {
            return "tinggi sekitar {$collection->first()} meter dari puncak. ";
        }

        return "tinggi sekitar {$collection->first()}-{$collection->last()} meter dari puncak. ";
    }

    public function asap(MagmaVar $var): string
    {
        switch ($var->var_asap) {
            case 'Nihil':
                return 'Asap kawah nihil. ';
            case 'Tidak Teramati':
                return 'Asap kawah tidak teramati. ';
            default:
                return "Teramati asap kawah utama berwarna {$this->warnaAsap($var->var_wasap->toArray())} dengan intensitas {$this->intensitasAsap($var->var_intasap->toArray())} {$this->tinggiAsap($var->var_tasap_min,$var->var_tasap)}";
        }
    }

    public function cuaca(MagmaVar $var): string
    {
        $collection = $var->var_cuaca->map(function ($cuaca) {
            return strtolower($cuaca);
        });

        if ($collection->count() == 1) {
            return "Cuaca {$collection->first()}";
        }

        return "Cuaca {$collection->first()} hingga {$collection->last()}";
    }

    public function kecepatanAngin(Collection $kecepatanAngin): string
    {
        $collection = $kecepatanAngin->map(function ($kecepatan) {
            return strtolower($kecepatan);
        });

        if ($collection->count() == 1) {
            return "Angin {$collection->first()}";
        }

        return "Angin {$collection->first()} hingga {$collection->last()}";
    }

    public function arahAngin(Collection $arahAngin): string
    {
        $collection = $arahAngin->map(function ($arah) {
            return strtolower($arah);
        });

        if ($collection->count() == 1) {
            return "ke arah {$collection->first()}. ";
        }

        $last = Str::replaceLast(
            ', ',
            ' dan ',
            strtolower(implode(', ', $collection->toArray()))
        );

        return "ke arah {$last}. ";
    }

    public function angin(MagmaVar $var): string
    {
        return "{$this->kecepatanAngin($var->var_kecangin)} {$this->arahAngin($var->var_arangin)}";
    }

    public function meteorologi(MagmaVar $var): string
    {
        return $this->cuaca($var) . ', ' . strtolower($this->angin($var));
    }

    public function suhu(MagmaVar $var): string
    {
        $collection = collect([
            $var->var_suhumin,
            $var->var_suhumax,
        ])->reject(function ($suhu) {
            return $suhu == 0;
        })->unique();

        if ($collection->isEmpty()) {
            return '';
        }

        if ($collection->count() == 1) {
            return "Suhu udara sekitar {$collection->first()}&deg;C. ";
        }

        return "Suhu udara sekitar {$collection->first()}-{$collection->last()}&deg;C. ";
    }

    public function kelembaban(MagmaVar $var): string
    {
        $collection = collect([
            $var->var_kelembabanmin,
            $var->var_kelembabanmax,
        ])->reject(function ($kelembaban) {
            return $kelembaban == 0;
        })->unique();

        if ($collection->isEmpty()) {
            return '';
        }

        if ($collection->count() == 1) {
            return "Kelembaban {$collection->first()}%. ";
        }

        return "Kelembaban {$collection->first()}-{$collection->last()}%. ";
    }

    public function tekanan(MagmaVar $var): string
    {
        $collection = collect([
            $var->var_tekananmin,
            $var->var_tekananmax,
        ])->reject(function ($tekanan) {
            return $tekanan == 0;
        })->unique();

        if ($collection->isEmpty()) {
            return '';
        }

        if ($collection->count() == 1) {
            return "Tekanan udara {$collection->first()} mmHg. ";
        }

        return "Tekanan udara {$collection->first()}-{$collection->last()} mmHg. ";
    }

    public function visual(MagmaVar $var): string
    {
        $visuals = [
            $this->visibility($var),
            $this->asap($var),
            $this->meteorologi($var),
            $this->suhu($var),
            $this->kelembaban($var),
            $this->tekanan($var),
        ];

        return implode($visuals);
    }

    public function warnaLetusan(Collection $warnaLetusan): string
    {
        if ($warnaLetusan->isEmpty()) {
            return 'abu letusan tidak teramati. ';
        }

        return 'kolom abu letusan berwarna ' . str_replace_last(', ', ' hingga ', strtolower(implode(', ', $warnaLetusan->toArray()))) . '. ';
    }

    public function tinggiLetusan(
        int $tinggiMinimum,
        int $tinggiMaksimum,
        Collection $warnaLetusan
    ): string {
        $collection = collect([
            $tinggiMinimum,
            $tinggiMaksimum
        ])->reject(function ($tinggi) {
            return $tinggi == 0;
        })->unique();

        if ($collection->isEmpty()) {
            return 'Terekam Gempa Letusan, namun secara visual tinggi letusan dan warna abu tidak teramati. ';
        }

        if ($collection->count() == 1) {
            return "Letusan teramati dengan tinggi {$collection->first()} meter dari puncak, {$this->warnaLetusan($warnaLetusan)}";
        }

        return "Letusan teramati dengan tinggi {$collection->first()}-{$collection->last()} meter dari puncak, {$this->warnaLetusan($warnaLetusan)}";
    }

    public function letusan(MagmaVar $var): string
    {
        return $var->var_lts ? $this->tinggiLetusan(
            $var->var_lts_tmin,
            $var->var_lts_tmax,
            $var->var_lts_wasap
        ) : '';
    }

    public function arahGuguran(Collection $arahGuguran): string
    {
        if ($arahGuguran->isEmpty()) {
            return "arah guguran tidak teramati. ";
        }

        return "guguran mengarah ke " . str_replace_last(', ', ' hingga ', strtolower(implode(', ', $arahGuguran->toArray()))) . '. ';
    }

    public function panjangGuguran(
        string $namaGempa,
        int $panjangMinimum,
        int $panjangMaximum,
        Collection $arahGuguran
    ): string {
        $collection = collect([
            $panjangMinimum,
            $panjangMaximum
        ])->reject(function ($panjang) {
            return $panjang == 0;
        })->unique();

        if ($collection->isEmpty()) {
            return "Terjadi {$namaGempa}, namun secara visual, jarak dan arah guguran tidak teramati. ";
        }

        if ($collection->count() == 1) {
            return "{$namaGempa} teramati dengan jarak {$collection->first()} meter dari puncak, {$this->arahGuguran($arahGuguran)}";
        }

        return "{$namaGempa} teramati dengan jarak {$collection->first()}-{$collection->last()} meter dari puncak, {$this->arahGuguran($arahGuguran)}";
    }

    public function guguran(MagmaVar $var): Collection
    {
        $gempas = collect([
            'apl' => 'Awan Panas Letusan',
            'apg' => 'Awan Panas Guguran',
            'gug' => 'Guguran'
        ])->transform(function ($gempa, $code) use ($var) {
            if ($var->{'var_' . $code}) {
                return $this->panjangGuguran(
                    $gempa,
                    $var->{'var_' . $code . '_rmin'},
                    $var->{'var_' . $code . '_rmax'},
                    collect($var->{'var_' . $code . '_alun'}),
                );
            }
        })->reject(function ($gempa) {
            return is_null($gempa);
        })->flatten();

        return $gempas;
    }

    public function kegempaan(MagmaVar $var): Collection
    {
        $gempas = collect([
            'lts' => 'Letusan/Erupsi',
            'apl' => 'Awan Panas Letusan',
            'apg' => 'Awan Panas Guguran',
            'gug' => 'Guguran',
            'hbs' => 'Hembusan',
            'hrm' => 'Harmonik',
            'tre' => 'Tremor Non-Harmonik',
            'tor' => 'Tornillo',
            'lof' => 'Low Frequency',
            'hyb' => 'Hybrid/Fase Banyak',
            'vtb' => 'Vulkanik Dangkal',
            'vta' => 'Vulkanik Dalam',
            'vlp' => 'Very Long Period',
            'tel' => 'Tektonik Lokal',
            'trs' => 'Terasa',
            'tej' => 'Tektonik Jauh',
            'dev' => 'Double Event',
            'gtb' => 'Getaran Banjir',
            'dpt' => 'Deep Tremor',
            'mtr' => 'Tremor Menerus'
        ])->transform(function ($namaGempa, $codeGempa) use ($var) {
            if ($var->{'var_' . $codeGempa}) {
                return "{$var->{'var_' .$codeGempa}} kali gempa {$namaGempa}";
            }
        })->reject(function ($gempa) {
            return is_null($gempa);
        })->flatten();

        return $gempas;
    }

    public function updateRekomendasi(MagmaVar $var): ?MagmaVar
    {
        $rekomendasi = MagmaVarRekomendasi::where('ga_code', $var->ga_code)
            ->where('status', $var->cu_status)
            ->orderByDesc('date')
            ->first();

        if (is_null($rekomendasi)) {
            return null;
        }

        $var->update([
            'magma_var_rekomendasi_id' => $rekomendasi->id
        ]);

        return $var->load('rekomendasi.lists');
    }

    public function rekomendasi(MagmaVar $var): Collection
    {
        if (is_null($var->rekomendasi)) {

            $rekomendasi = $this->updateRekomendasi($var);

            if (!is_null($rekomendasi)) {
                return $rekomendasi->rekomendasi->lists->pluck('rekomendasi');
            }

            return collect(preg_split('/\n|\r\n/', $var->var_rekom))->reject(function ($rekomendasi) {
                return empty($rekomendasi);
            });
        }

        return $var->rekomendasi->lists->pluck('rekomendasi');
    }

    public function transformed(Collection $gadds): Collection
    {
        $filtered = $gadds->reject(function ($gadd) {
            return is_null($gadd->var);
        })->transform(function ($gadd) {
            return [
                'name' => $gadd->ga_nama_gapi,
                'status' => $this->status($gadd->var),
                'date' => $gadd->var->var_data_date,
                'visual' => [
                    'gunung_api' => $this->visual($gadd->var),
                    'letusan' => $this->letusan($gadd->var),
                    'guguran' => $this->guguran($gadd->var),
                ],
                'kegempaan' => $this->kegempaan($gadd->var),
                'rekomendasi' => $this->rekomendasi($gadd->var),
            ];
        });

        return $filtered->groupBy('status')->sortKeysDesc();
    }

    public function cache(): Collection
    {
        return $this->isCached ?
            Cache::rememberForever("laporan-harian-{$this->date->format('Ymd')}", function () {
                return $this->transformed($this->gadds());
            }) : $this->transformed($this->gadds());
    }

    public function groupedByStatus()
    {
        return $this->cache()->isEmpty() ? abort(404) : $this->cache();
    }

    public function isCached(?bool $isCached = null)
    {
        if (is_null($isCached)) {
            return $this->isCached = now()->format('Ymd') === $this->date->format('Ymd') ? false : true;
        }

        return $this->isCached = $isCached;
    }

    /**
     * Get date
     *
     * @param string|null $date
     * @return Carbon
     */
    public function date(?string $date = null)
    {
        if (is_null($date)) {
            return $this->date = now();
        }

        $validator = Validator::make([
            'date' => $date
        ], [
            'date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        return $this->date = Carbon::createFromFormat('Y-m-d', $date);
    }

    /**
     * Get gadds
     *
     * @param Carbon $date
     * @return Collection
     */
    public function gadds(): Collection
    {
        $gadds = Gadd::select('ga_code', 'ga_nama_gapi')
            ->whereNotIn('ga_code', ['TEO'])
            ->where('laporan_harian', 1)
            ->orderBy('ga_nama_gapi')
            ->with([
                'var' => function ($query) {
                    $query->where('var_noticenumber', 'like', "{$this->date->format('Ymd')}%");
                },
                'var.rekomendasi.lists',
            ])
            ->get();

        return $gadds;
    }
}