<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchGunungApi;
use Carbon\Carbon;
use App\Gadd;
use App\MagmaVar;
use App\VarDaily;
use App\Http\Resources\VarResource;
use App\Http\Resources\VarCollection;
use App\Http\Resources\GunungApiCollection;

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

        // return $vars = new GunungApiCollection($gadds);

        // return view('tes',compact('vars'));
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
            
        // return $var;
        
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
        $tipe = $request->jenis;
        $code = strtoupper($request->gunungapi);
        $bulan = $request->input('bulan',null);        
        $start = $request->input('start',null);
        $end = $request->input('end',null);

        switch ($tipe) {
            case '0':
                $end = Carbon::createFromFormat('Y-m-d',$start)->addDays(14)->format('Y-m-d');
                break;
            case '1':
                $start = $bulan;
                $end = Carbon::createFromFormat('Y-m-d',$bulan)->endOfMonth()->format('Y-m-d');
                break;
            
            default:
                $end = $end;
                break;
        }

        // return $end;

        $vars = MagmaVar::where('code_id', 'like', $code)
                    ->whereBetween('var_data_date', [$start, $end])
                    ->orderBy('var_data_date','asc')
                    ->orderBy('created_at','desc')
                    ->paginate(15);

        // $vars->appends([
        //     'gunungapi' => $code,
        //     'jenis' => $tipe,
        //     'bulan' => $bulan,
        //     'start' => $start,
        //     'end' => $end,
        // ]);


        Carbon::setLocale('id');

        return view('gunungapi.laporan.search',compact('vars','gadds'));

    }
}
