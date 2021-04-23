<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Vona;
use App\v1\Gadd;

class VonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = Vona::select('no')->orderBy('no','desc')->first();
        $page = $request->has('vona_page') ? $request->vona_page : 1;

        $vonas = Cache::remember('v1/vonas-'.$last->no.'-page-'.$page, 60, function() {
                    return Vona::orderBy('issued','desc')
                            ->where('sent',1)
                            ->paginate(30,['*'],'vona_page');
                });

        return view('v1.vona.index',compact('vonas'));
    }

    protected function download($request)
    {
        return 'download';
    }

    protected function filterResult($request)
    {
        $vonas = Vona::where('ga_code',$request->code)->get();

        return $vonas;
    }

    public function filter(Request $request)
    {
        if ($request->has('form') AND $request->form == 'download') {
            return $this->download($request);
        }

        $gadds = Cache::remember('chambers/v1/gadds', 240, function () {
            return Gadd::select('ga_code', 'ga_nama_gapi')
                ->whereNotIn('ga_code', ['TEO'])
                ->orderBy('ga_nama_gapi', 'asc')
                ->get();
        });
        
        return view('v1.vona.filter', [
            'gadds' => $gadds,
            'vonas' => count($request->all()) ? $this->filterResult($request) : [],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\v1\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show($no)
    {
        $vona = Cache::remember('v1/vona-show-'.$no, 120, function () use($no) {
            return Vona::findOrFail($no); 
        });

        return view('v1.vona.show',compact('vona'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($no)
    {
        $vona = Vona::findOrFail($no);
        // $vona->delete();
        
        return redirect()->route('chambers.v1.vona.index')
                ->with('flash_message','VONA berhasil dihapus!');
    }
}
