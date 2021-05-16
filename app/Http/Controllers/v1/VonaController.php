<?php

namespace App\Http\Controllers\v1;

use App\Exports\v1\VonaExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Vona;
use App\v1\Gadd;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

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

    protected function vonaIsEmpty($vonas)
    {
        if ($vonas->isEmpty()) {
            request()->session()->forget('empty');
            throw ValidationException::withMessages([
                'empty' => ['Tidak bisa didownload!'],
            ]);
        }
    }

    protected function export($request)
    {
        $vonas =  $this->filterResult($request)->get();

        $this->vonaIsEmpty($vonas);

        $vonas->transform(function ($vona) {
            return [
                'gunung_api' => $vona->ga_nama_gapi,
                'smithsonian_id' => $vona->ga_id_smithsonian,
                'issued_utc' => $vona->issued_time,
                'current_code' => $vona->cu_avcode,
                'previous_code' => $vona->pre_avcode,
                'activity_summary' => $vona->volcanic_act_summ,
                'volcano_cloud_height' => $vona->vc_height,
                'other_information' => $vona->other_vc_info,
            ];
        });

        $export = new VonaExport($vonas->toArray());

        return Excel::download($export, 'vona.xlsx');
    }

    protected function filterResult(Request $request)
    {
        $validated = $request->validate([
            'code' => 'bail|required',
            'colors' => 'required|array',
            'colors.*' => 'in:red,orange,yellow,green,unassigned',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after:start_date'
        ]);

        $vonas = Vona::where('ga_code', $validated['code'])
            ->select('no','issued','issued_time','ga_nama_gapi','ga_code', 'ga_id_smithsonian', 'cu_avcode', 'pre_avcode', 'vc_height', 'summit_elevation', 'volcanic_act_summ', 'vc_height_text', 'other_vc_info', 'remarks','sender','nama')
            ->where('sent', 1)
            ->whereIn('cu_avcode', array_map('strtoupper', $validated['colors']))
            ->whereBetween('issued_time', [
                $validated['start_date'],
                $validated['end_date'],
            ])
            ->orderBy('issued_time','desc');

        return $vonas;
    }

    protected function result(Request $request)
    {
        $vonas = $this->filterResult($request)->paginate(15);

        return $vonas;
    }

    public function filter(Request $request)
    {
        $vonas = collect([]);

        if ($request->has('form') AND $request->form == 'download') {
            return $this->export($request);
        }

        if ($request->has('form') and $request->form == 'filter') {
            $vonas = $this->result($request);
            $vonas->isEmpty() ?
                $request->session()->flash('empty', 'Hasil pencarian tidak ditemukan!') :
                $request->session()->forget('empty');
        }

        $gadds = Cache::remember('chambers/v1/gadds', 240, function () {
            return Gadd::select('ga_code', 'ga_nama_gapi')
                ->whereNotIn('ga_code', ['TEO'])
                ->orderBy('ga_nama_gapi', 'asc')
                ->get();
        });

        return view('v1.vona.filter', [
            'gadds' => $gadds,
            'vonas' => $vonas->isNotEmpty() ? $vonas : [],
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
