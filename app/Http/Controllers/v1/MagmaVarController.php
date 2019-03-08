<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar as OldVar;
use App\v1\Gadd;
use App\v1\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class MagmaVarController extends Controller
{
    use VisualAsap,DeskripsiGempa;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vars = OldVar::select(
                'no','var_data_date','periode',
                'ga_nama_gapi','cu_status','var_nama_pelapor')
                ->orderBy('var_data_date','desc')
                ->orderBy('periode','desc')
                ->paginate(30);

        return view('v1.gunungapi.laporan.index',compact('vars'));
    }

    /**
     * Display a filtering listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function filter(Request $request)
    {
        $gadds = Cache::remember('gadds', 120, function () {
                    return Gadd::select('no','ga_code','ga_nama_gapi')
                        ->whereNotIn('ga_code',['TEO','SBG'])
                        ->orderBy('ga_nama_gapi','asc')
                        ->get();
                });
        
        $users = Cache::remember('users', 120, function () {
                    return User::select('id','vg_nip','vg_nama')
                        ->where('vg_bid','Pengamatan dan Penyelidikan Gunungapi')
                        ->orderBy('vg_nama','asc')
                        ->get();
                });

        if (count($request->all()))
        {
            $nip = $request->nip == 'all' ? '%' : $request->input('nip','%');
            $code = $request->gunungapi == 'all' ? '%' : strtoupper($request->input('gunungapi','%'));
            $periode = $request->tipe == 'all' ? '%' : $request->input('tipe','%').' Jam';
            $bulan = $request->input('bulan', Carbon::parse('first day of January')->format('Y-m-d'));        
            $start = $request->input('start', Carbon::parse('first day of January')->format('Y-m-d'));
            $end = $request->input('end', Carbon::now()->format('Y-m-d'));

            switch ($request->jenis) {
                case '0':
                    $end = Carbon::createFromFormat('Y-m-d',$start)->addDays(13)->format('Y-m-d');
                    break;
                case '1':
                    $start = Carbon::createFromFormat('Y-m-d',$bulan)->startOfMonth()->format('Y-m-d');
                    $end = Carbon::createFromFormat('Y-m-d',$bulan)->endOfMonth()->format('Y-m-d');
                    break;
                default:
                    $end = $end;
                    break;
            }

            $vars = OldVar::select('no','ga_nama_gapi','var_data_date','var_perwkt','periode','var_nama_pelapor')
                        ->where('ga_code', 'like', $code)
                        ->whereBetween('var_data_date', [$start, $end])
                        ->where('var_perwkt','like',$periode)
                        ->where('var_nip_pelapor','like',$nip)
                        ->orderBy('var_data_date','asc')
                        ->orderBy('var_data_date','desc');
            
            $count = $vars->count();
    
            $vars = $vars->paginate(31);

            return view('v1.gunungapi.laporan.filter',compact('vars','gadds','users'))->with('flash_result',
            $count.' laporan berhasil ditemukan');
        }

        return view('v1.gunungapi.laporan.filter',compact('gadds','users'))->with('flash_message',
        'Kriteria pencarian tidak ditemukan/belum ada');
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
        $var = OldVar::findOrFail($id);

        $others = OldVar::select('no','ga_code','var_data_date','periode','cu_status')
                    ->where('ga_code',$var->ga_code)
                    ->where('var_data_date','like',$var->var_data_date->format('Y-m-d'))
                    ->whereIn('periode',['00:00-06:00','06:00-12:00','12:00-18:00','18:00-24:00','00:00-24:00'])
                    ->get();
        
        $asap = (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap,
        ];

        $visual = $this->visibility($var->var_visibility->toArray())
                ->asap($var->var_asap, $asap)
                ->cuaca($var->var_cuaca->toArray())
                ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                ->getVisual();
        
        $gempa = $this->getDeskripsiGempa($var);

        return view('v1.gunungapi.laporan.show',compact('var','others','visual','gempa'));
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
