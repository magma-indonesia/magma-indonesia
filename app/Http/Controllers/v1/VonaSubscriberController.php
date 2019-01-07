<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\VonaSubscriber as Subscription;

class VonaSubscriberController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware(
            'role:Super Admin',
            ['except' => 'index','show']
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subs = Subscription::paginate(30,['*'],'sub_page');

        return view('v1.subscribers.index',compact('subs'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sub = Subscription::findOrFail($id);
        $sub->subscribe = 1;

        if ($sub->save())
        {
            return back()->with('flash_message','Subscribe berhasil!');
        };

        return back()->with('flash_message','Subscribe gagal :(');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub = Subscription::findOrFail($id);
        $sub->subscribe = 0;

        if ($sub->save())
        {
            return back()->with('flash_message','Unsub berhasil!');
        };

        return back()->with('flash_message','Unsub gagal :(');

    }
}
