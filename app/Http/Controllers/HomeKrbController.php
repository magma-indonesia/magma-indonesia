<?php

namespace App\Http\Controllers;

use App\HomeKrb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HomeKrbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('chambers.krb-gunungapi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('chambers.krb-gunungapi.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
            'expired_at' => 'nullable|date_format:Y-m-d'
        ]);

        $url = Str::endsWith($request->url, '0') ? $request->url : $request->url.'/0';

        HomeKrb::create([
            'url' => $url,
            'expired_at' => $request->expired_at
        ]);

        Cache::forget('home:krb');
        Cache::rememberForever('home:krb', function () {
            return HomeKrb::latest()->first(); 
        });

        return redirect()->route('chambers.krb-gunungapi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HomeKrb  $homeKrb
     * @return \Illuminate\Http\Response
     */
    public function show(HomeKrb $homeKrb)
    {
        return redirect()->route('chambers.krb-gunungapi.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HomeKrb  $homeKrb
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeKrb $homeKrb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HomeKrb  $homeKrb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomeKrb $homeKrb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HomeKrb  $homeKrb
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomeKrb $homeKrb)
    {
        //
    }
}
