<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchGunungApi;
use Carbon\Carbon;
use App\Gadd;
use App\MagmaVar;
use App\VarDaily;

class ActivityController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     

        $vars = MagmaVar::orderBy('var_data_date','desc')->orderBy('created_at','desc')->simplePaginate(30)->setPageName('ga_page');;
        $gadds = Gadd::orderBy('name')->whereNotIn('code',['TEO','SBG'])->get();

        Carbon::setLocale('id'); 
        return view('activities.index',compact('vars','gadds'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        // return $request;
        $var = MagmaVar::where('noticenumber',$id)->with([

            'user:name,nip',
            'visual',
            'visual.asap',
            'klimatologi',
            'gempa'
            
            ])->first();

        return $var;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search($jenis, SearchGunungApi $request)
    {
        $tipe = $request->jenis;
        $code = strtoupper($request->gunungapi);
        $bulan = $request->input('bulan',null);        
        $start = $request->input('start',null);
        $end = $request->input('end',null);

        // switch ($tipe) {
        //     case '0':
        //         # code...
        //         break;
            
        //     default:
        //         # code...
        //         break;
        // }

        $vars = MagmaVar::where('code_id', 'like', $code)
                    ->whereBetween('var_data_date', [$start, $end])
                    ->orderBy('var_data_date','desc')
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

        if ($jenis == 'ga')
        {
            return view('activities.gunungapi.search',compact('vars','gadds'));
            
        }

    }

}
