<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Absensi;
use Illuminate\Support\Facades\Cache;

class AbsensiController extends Controller
{
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

        return view('v1.absensi.index',compact('absensis'));
    }
}
