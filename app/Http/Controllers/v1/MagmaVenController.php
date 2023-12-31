<?php

namespace App\Http\Controllers\v1;

use App\Exports\VenExportDeskripsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use App\v1\MagmaVar;
use App\v1\Gadd;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = MagmaVen::select('erupt_id')->orderBy('erupt_id','desc')->first();
        $page = $request->has('page') ? $request->page : 1;
        $code = $request->has('code') ? $request->code : false;

        $records = Cache::remember('v1/home/vens:records:'.$last->erupt_id, 60, function() {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi')
                ->select('ga_code')
                ->distinct('ga_code')
                ->get();
        });

        $vens = $code ? $this->filteredVen($code,$last,$page) : $this->nonFilteredVen($last,$page);

        return view('v1.gunungapi.ven.index',compact('vens','records'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ven = MagmaVen::findOrFail($id);
        $visual = $ven->erupt_vis == '1' 
                    ? $this->visualTeramati($ven)
                    : $this->visualTidakTeramati($ven);

        return view('v1.gunungapi.ven.show',compact('ven','visual'));
    }

    protected function seismik($ven): ?string
    {
        $seismik = $ven->erupt_amp ? 'Erupsi ini terekam di seismograf dengan amplitudo maksimum ' . $ven->erupt_amp . ' mm dan durasi ' . $ven->erupt_drs . ' detik.' : null;
        return $seismik;
    }

    protected function teramati($ven): string
    {
        $asl = $ven->erupt_tka + $ven->gunungapi->ga_elev_gapi;

        $wasap = !empty($ven->erupt_wrn)
            ? str_replace_last(', ', ' hingga ', strtolower(implode(', ', $ven->erupt_wrn)))
            : strtolower($ven->erupt_wrn[0]);

        $intensitas = !empty($ven->erupt_int)
            ? str_replace_last(', ', ' hingga ', strtolower(implode(', ', $ven->erupt_int)))
            : strtolower($ven->erupt_int[0]);

        $arah = !empty($ven->erupt_arh)
            ? str_replace_last(', ', ' dan ', strtolower(implode(', ', $ven->erupt_arh)))
            : strtolower($ven->erupt_arh[0]);

        $visual = 'Telah terjadi erupsi G. ' . $ven->gunungapi->ga_nama_gapi . ',' . $ven->gunungapi->ga_prov_gapi . ' pada hari ' . Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') . ', pukul ' . $ven->erupt_jam . ' ' . $ven->gunungapi->ga_zonearea . ' dengan tinggi kolom abu teramati &plusmn; ' . $ven->erupt_tka . ' m di atas puncak (&plusmn; ' . $asl . ' m di atas permukaan laut). Kolom abu teramati berwarna ' . $wasap . ' dengan intensitas ' . $intensitas . ' ke arah ' . $arah . '. ';

        return $visual;
    }

    protected function tidakTeramati($ven): string
    {
        return 'Telah terjadi erupsi G. ' . $ven->gunungapi->ga_nama_gapi . ',' . $ven->gunungapi->ga_prov_gapi . ' pada hari ' . Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') . ', pukul ' . $ven->erupt_jam . ' ' . $ven->gunungapi->ga_zonearea . '. Visual letusan tidak teramati. ';
    }
    

    /**
     * Visual letusan teramati
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function visualTeramati($ven)
    {
        $seismik = $this->seismik($ven) ?: '' ;

        $deskripsi = "{$this->teramati($ven)}{$seismik}";

        return $deskripsi;
    }

    /**
     * visualTidakTeramati
     *
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function visualTidakTeramati($ven)
    {
        $seismik = $this->seismik($ven) ?: '';

        $deskripsi = "{$this->tidakTeramati($ven)}{$seismik}";

        return $deskripsi;
    }

    protected function filter(Request $request)
    {
        $gadds = Cache::remember('v1/gadds', 120, function () {
            return Gadd::select('no','ga_code','ga_nama_gapi')
                ->whereNotIn('ga_code',['TEO','SBG'])
                ->orderBy('ga_nama_gapi','asc')
                ->get();
        });

        if (count($request->all()))
        {
            $code = strtolower($request->gunungapi) == 'all' ? '%' : $request->gunungapi;
            $periode = $request->start.' hingga '.$request->end;
            $jenis = $request->source;
            $erupsi = collect([]);
            $gempas = collect([]);

            if ($request->source == 'all')
            {
                $gempas = collect(
                [
                    [
                        'jenis' => 'letusan',
                        'nama' => 'Letusan'
                    ],
                    [
                        'jenis' => 'awan_panas_letusan',
                        'nama' => 'Awan Panas Letusan'
                    ],[
                        'jenis' => 'awan_panas_guguran',
                        'nama' => 'Awan Panas Guguran'
                    ],[
                        'jenis' => 'guguran', 
                        'nama' => 'Guguran'
                    ],[
                        'jenis' => 'hembusan', 
                        'nama' => 'Hembusan'
                    ]
                ]);

                $erupsi = MagmaVar::select('ga_code','ga_nama_gapi','var_data_date','var_lts','var_apg','var_apl','var_gug','var_hbs')
                            ->selectRaw('SUM(var_lts) as jumlah_letusan')
                            ->selectRaw('SUM(var_apg) as jumlah_awan_panas_guguran')
                            ->selectRaw('SUM(var_apl) as jumlah_awan_panas_letusan')
                            ->selectRaw('SUM(var_gug) as jumlah_guguran')
                            ->selectRaw('SUM(var_hbs) as jumlah_hembusan')
                            ->whereBetween('var_data_date',[$request->start,$request->end])
                            ->where('ga_code','like',$code)
                            ->where('var_perwkt','24 Jam')
                            ->groupBy('magma_var.ga_nama_gapi')
                            ->get();

                $erupsi = $erupsi->reject(function ($value, $key) {
                    return (($value['jumlah_letusan'] == 0) AND ($value['jumlah_awan_panas_guguran'] == 0) AND ($value['jumlah_awan_panas_letusan'] == 0) AND ($value['jumlah_guguran'] == 0) AND ($value['jumlah_hembusan'] == 0));
                });
            }

            if ($request->source == 'lts')
            {
                $gempas = collect(['jenis' => 'erupsi','nama' => 'Letusan']);
                $erupsi = MagmaVar::select('ga_code','ga_nama_gapi','var_data_date','var_lts')
                            ->selectRaw('SUM(var_lts) as jumlah_letusan')
                            ->where('ga_code','like',$code)
                            ->where('var_perwkt','24 Jam')
                            ->where('var_lts','>',0)
                            ->whereBetween('var_data_date',[$request->start,$request->end])
                            ->groupBy('magma_var.ga_nama_gapi')
                            ->get();
            }

            if ($request->source == 'apg')
            {
                $gempas = collect(
                [
                    [
                        'jenis' => 'awan_panas_letusan',
                        'nama' => 'Awan Panas Letusan'
                    ],[
                        'jenis' => 'awan_panas_guguran',
                        'nama' => 'Awan Panas Guguran'
                    ],[
                        'jenis' => 'guguran', 
                        'nama' => 'Guguran'
                    ]
                ]);
                $erupsi = MagmaVar::select('ga_code','ga_nama_gapi','var_data_date','var_apg','var_apl','var_gug')
                            ->selectRaw('SUM(var_apg) as jumlah_awan_panas_guguran')
                            ->selectRaw('SUM(var_apl) as jumlah_awan_panas_letusan')
                            ->selectRaw('SUM(var_gug) as jumlah_guguran')
                            ->whereBetween('var_data_date',[$request->start,$request->end])
                            ->where('ga_code','like',$code)
                            ->where('var_perwkt','24 Jam')
                            ->groupBy('magma_var.ga_nama_gapi')
                            ->get();

                $erupsi = $erupsi->reject(function ($value, $key) {
                    return (($value['jumlah_awan_panas_guguran'] == 0) AND ($value['jumlah_awan_panas_letusan'] == 0) AND ($value['jumlah_guguran'] == 0));
                });
            }

            if ($request->source == 'hbs')
            {
                $gempas = collect([['jenis' => 'hembusan','nama' => 'Hembusan']]);
                $erupsi = MagmaVar::select('ga_code','ga_nama_gapi','var_data_date','var_hbs')
                            ->selectRaw('SUM(var_hbs) as jumlah_hembusan')
                            ->where('ga_code','like',$code)
                            ->where('var_perwkt','24 Jam')
                            ->where('var_hbs','>',0)
                            ->whereBetween('var_data_date',[$request->start,$request->end])
                            ->groupBy('magma_var.ga_nama_gapi')
                            ->get();
            }

            return view('v1.gunungapi.ven.result', compact('gadds','erupsi','periode','jenis','gempas'));     
        }

        return view('v1.gunungapi.ven.filter', compact('gadds'))->with('flash_message',
        'Kriteria pencarian tidak ditemukan/belum ada');
    }

    protected function filteredVen($code,$last,$page)
    {
        return Cache::remember('v1/vens:'.$code.':'.$last->erupt_id.'-page-'.$page, 120, function() use($code) {
            return MagmaVen::where('ga_code', $code)->orderBy('erupt_tsp','desc')->paginate(30);
        });
    }

    protected function nonFilteredVen($last,$page)
    {
        return Cache::remember('v1/vens:'.$last->erupt_id.'-page-'.$page, 120, function() {
            return MagmaVen::orderBy('erupt_tsp','desc')->paginate(30);
        });
    }

    public function export()
    {
        $vens = Cache::remember('export-vens', 10, function () {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea')->get();
        });

        $vens->transform(function ($ven) {
            return [
                'gunung_api' => $ven->gunungapi->ga_nama_gapi,
                'waktu_kejadian' => $ven->erupt_tgl.' '.$ven->erupt_jam.':00',
                'zona_waktu' => $ven->gunungapi->ga_zonearea,
                'letusan_teramati' => $ven->erupt_vis ? 'Ya' : 'Tidak',
                'tinggi_letusan' => $ven->erupt_vis ? $ven->erupt_tka : 0,
                'deskripsi_visual_letusan' => $ven->erupt_vis == '1'  ? $this->teramati($ven) : $this->tidakTeramati($ven),
                'deskripsi_seismik' => $this->seismik($ven) ?: '-',
                'rekomendasi' => strip_tags(nl2br($ven->erupt_rek)),
            ];
        });

        // return view('v1.exports.vens', compact('vens'));

        $export = new VenExportDeskripsi($vens->toArray());

        return Excel::download($export, 'ven.xlsx');
    }
}
