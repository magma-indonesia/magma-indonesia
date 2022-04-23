<?php

namespace App\Http\Controllers;

use App\Blacklist;
use App\StatistikAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlacklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blacklists = Blacklist::all();

        $accesess = StatistikAccess::where('hit','>',1000)
            ->orderBy('updated_at','desc')
            ->limit(20)
            ->get();

        return view('blacklist.index', [
            'blacklists' => $blacklists,
            'accesses' => $accesess,
            'diffs' => $accesess->whereNotIn('ip_address', $blacklists->pluck('ip_address'))->values(),
        ]);
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
        Blacklist::firstOrCreate(['ip_address' => $request->ip]);

        return [
            'message' => "$request->ip berhasil ditambahkan",
            'success' => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blacklist $blacklist)
    {
        return $blacklist->delete() ? [
            'message' => $blacklist->ip_address . ' berhasil dihapus',
            'success' => true
        ] : [
            'message' => $blacklist->ip_address . ' gagal dihapus',
            'success' => false
        ];
    }
}
