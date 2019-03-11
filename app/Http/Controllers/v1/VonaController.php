<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Vona;
use App\Http\Resources\v1\VonaResource;
use App\Http\Resources\v1\VonaCollection;

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
