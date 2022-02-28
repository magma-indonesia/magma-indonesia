<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\Http\Controllers\Controller;
use App\v1\Gadd;
use App\v1\MagmaVar;
use App\v1\MagmaVarListRekomendasi;
use App\v1\MagmaVarRekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanHarianController extends Controller
{
    protected function status(MagmaVar $var) : string
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

    protected function visibility(MagmaVar $var) : string
    {
        if ($var->var_visibility->count() == 1 && in_array('Jelas', $var->var_visibility->toArray())) {
            return "Gunung api terlihat jelas. ";
        }

        if (in_array('Jelas', $var->var_visibility->toArray())) {
            return "Gunung api terlihat jelas hingga tertutup kabut. ";
        }

        return "Gunung api tertutup kabut. ";
    }

    protected function warnaAsap(array $warnaAsap): string
    {
        return Str::replaceLast(
            ', ',
            ' dan ',
            strtolower(implode(', ', $warnaAsap))
        );
    }

    protected function intensitasAsap(array $intensitasAsap): string
    {
        return Str::replaceLast(
            ', ',
            ' hingga ',
            strtolower(implode(', ', $intensitasAsap))
        );
    }

    protected function tinggiAsap(int $tinggiMinimum, int $tinggiMaksimum): string
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

    protected function asap(MagmaVar $var): string
    {
        switch ($var->var_asap) {
            case 'Nihil':
                return 'Asap kawah nihil. ';
            case 'Tidak Teramati':
                return 'Asap kawah tidak teramati. ';
            default:
                return "Teramati asap kawah utama berwarna {$this->warnaAsap($var->var_wasap->toArray())} dengan intensitas {$this->intensitasAsap($var->var_intasap->toArray())} {$this->tinggiAsap($var->var_tasap_min, $var->var_tasap)}";
        }
    }

    protected function cuaca(MagmaVar $var): string
    {
        $collection = $var->var_cuaca->map(function ($cuaca) {
            return strtolower($cuaca);
        });

        if ($collection->count() == 1) {
            return "Cuaca {$collection->first()}";
        }

        return "Cuaca {$collection->first()} hingga {$collection->last()}";
    }

    protected function kecepatanAngin(Collection $kecepatanAngin): string
    {
        $collection = $kecepatanAngin->map(function ($kecepatan) {
            return strtolower($kecepatan);
        });

        if ($collection->count() == 1) {
            return "Angin {$collection->first()}";
        }

        return "Angin {$collection->first()} hingga {$collection->last()}";
    }

    protected function arahAngin(Collection $arahAngin): string
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

    protected function angin(MagmaVar $var): string
    {
        return "{$this->kecepatanAngin($var->var_kecangin)} {$this->arahAngin($var->var_arangin)}";
    }

    protected function meteorologi(MagmaVar $var): string
    {
        return $this->cuaca($var).', '.strtolower($this->angin($var));
    }

    protected function suhu(MagmaVar $var): string
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

    protected function kelembaban(MagmaVar $var): string
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

    protected function tekanan(MagmaVar $var): string
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

    protected function visual(MagmaVar $var): string
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

    protected function warnaLetusan(Collection $warnaLetusan): string
    {
        if ($warnaLetusan->isEmpty()) {
            return 'abu letusan tidak teramati. ';
        }

        return 'kolom abu letusan berwarna ' . str_replace_last(', ', ' hingga ', strtolower(implode(', ', $warnaLetusan->toArray()))) . '. ';
    }

    protected function tinggiLetusan(
        int $tinggiMinimum,
        int $tinggiMaksimum,
        Collection $warnaLetusan): string
    {
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

    protected function letusan(MagmaVar $var): string
    {
        return $var->var_lts ? $this->tinggiLetusan(
            $var->var_lts_tmin,
            $var->var_lts_tmax,
            $var->var_lts_wasap) : '';
    }

    protected function arahGuguran(Collection $arahGuguran): string
    {
        if ($arahGuguran->isEmpty()) {
            return "arah guguran tidak teramati. ";
        }

        return "guguran mengarah ke " . str_replace_last(', ', ' hingga ', strtolower(implode(', ', $arahGuguran->toArray()))) . '. ';
    }

    protected function panjangGuguran(
        string $namaGempa,
        int $panjangMinimum,
        int $panjangMaximum,
        Collection $arahGuguran): string
    {
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

    protected function guguran(MagmaVar $var): Collection
    {
        $gempas = collect([
            'apl' => 'Awan Panas Letusan',
            'apg' => 'Awan Panas Guguran',
            'gug' => 'Guguran'
        ])->transform(function ($gempa, $code) use($var) {
            if ($var->{'var_'.$code}) {
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Gadd::select('ga_code', 'ga_nama_gapi')
            ->whereNotIn('ga_code', ['TEO'])
            ->where('laporan_harian',1)
            ->orderBy('ga_nama_gapi')
            ->with('var.rekomendasi.lists')
            ->get();

        $gadds->transform(function ($gadd) {
            return [
                'name' => $gadd->ga_nama_gapi,
                'status' => $this->status($gadd->var),
                'visual' => [
                    'gunung_api' => $this->visual($gadd->var),
                    'letusan' => $this->letusan($gadd->var),
                    'guguran' => $this->guguran($gadd->var),
                ],
                'rekomendasi' => collect($gadd->var->rekomendasi->lists->pluck('rekomendasi')),
            ];
        });

        return view('v1.home.laporan-harian', [
            'gadds' => $gadds
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
