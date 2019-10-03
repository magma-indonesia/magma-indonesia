<?php

namespace App\Http\Controllers\v1;

use App\v1\Absensi;
use App\v1\Kantor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AbsensiController extends Controller
{

    protected function getStatsistic()
    {
        $pemakai = Kantor::withCount('absensi')->whereNotIn('obscode',['PVG','BTK'])
                        ->orderBy('obscode')->get();

        $sebulan = Kantor::whereHas('absensi', function($query) {
                            $query->whereBetween('date_abs',[now()->subDays(30)->format('Y-m-d'), now()->format('Y-m-d')]);
                        })
                        ->whereNotIn('obscode',['PVG','BTK'])
                        ->orderBy('obscode')->get();

        return collect(['pemakai' => $pemakai,
                        'sebulan' => $sebulan]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = Absensi::select('id_abs','updated_at')->orderBy('id_abs','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $absensis = Cache::remember('v1/absensis-'.strtotime($last->updated_at).'-page-'.$page, 15, function() {
            return Absensi::with(['user','pos'])
                    ->where('date_abs',now()->format('Y-m-d'))
                    ->orderBy('id_abs','desc')
                    ->paginate(30);
        });

        $statistic = Cache::remember('v1/absensis:statistic-'.strtotime($last->updated_at), 15, function() {
            return $this->getStatsistic();
        });

        $pemakai_count = $statistic['pemakai']->count();
        $sebulan_count = $statistic['sebulan']->count();

        return view('v1.absensi.index',compact('absensis','statistic','pemakai_count','sebulan_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('chambers.v1.absensi.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return redirect()->route('chambers.v1.absensi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Absensi::with('user','pos')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return redirect()->route('chambers.v1.absensi.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Absensi $absensi)
    {
        return redirect()->route('chambers.v1.absensi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Absensi $absensi)
    {
        return redirect()->route('chambers.v1.absensi.index');
    }
}
