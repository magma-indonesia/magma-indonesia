<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchGunungApi;
use Carbon\Carbon;
use App\Gadd;
use App\MagmaVar;
use App\VarDaily;
use App\User;
use App\Http\Resources\VarResource;
use App\Http\Resources\VarCollection;

class ActivityGaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vars = MagmaVar::orderBy('var_data_date','desc')
                    ->orderBy('created_at','desc')
                    ->simplePaginate(15);

        $gadds = Gadd::orderBy('name')
                    ->whereNotIn('code',['TEO','SBG'])
                    ->get();

        Carbon::setLocale('id'); 
        return view('gunungapi.laporan.index',compact('vars','gadds'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $var = MagmaVar::where('noticenumber',$id)->first();

        Carbon::setLocale('id');             
        $var = new VarResource($var);
        
        return view('gunungapi.laporan.show', compact('var'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $gadds = Gadd::orderBy('name')
                    ->whereNotIn('code',['TEO','SBG'])
                    ->get();

        $users = User::whereHas('bidang', function($query){
                    $query->where('user_bidang_desc_id','like',2);
                })->orderBy('name')->get();

        $request->flash();

        if (count($request->all()) >0 )
        {

            switch ($request->nip) {
                case 'all':
                    $nip = '%';
                    break;
                default:
                    $nip = $request->input('nip','%');
                    break;
            }

            switch ($request->gunungapi) {
                case 'all':
                    $code = '%';
                    break;
                default:
                    $code = strtoupper($request->input('gunungapi','%'));
                    break;
            }
    
            switch ($request->tipe) {
                case 'all':
                    $periode = '%';
                    break;
                default:
                    $periode = $request->input('tipe','%');
                    break;
            }
    
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
    
            $vars = MagmaVar::where('code_id', 'like', $code)
                        ->whereBetween('var_data_date', [$start, $end])
                        ->where('var_perwkt','like',$periode)
                        ->where('nip_pelapor','like',$nip)
                        ->orderBy('var_data_date','asc')
                        ->orderBy('created_at','desc');
            
            $count = $vars->count();
    
            $vars = $vars->paginate(31);
    
            Carbon::setLocale('id');

            return view('gunungapi.laporan.search',compact('input','vars','gadds','users'))->with('flash_result',
            $count.' laporan berhasil ditemukan');
        }

        return view('gunungapi.laporan.search',compact('input','gadds','users'))->with('flash_message',
        'Kriteria pencarian tidak ditemukan/belum ada');

    }
}
