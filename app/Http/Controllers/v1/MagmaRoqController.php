<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaRoq;

class MagmaRoqController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware(
            'role:Super Admin|Staff MGB',
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
        $roqs = MagmaRoq::orderBy('datetime_utc','desc')
            ->paginate(30,['*'],'roq_page');

        return view('v1.gempabumi.index',compact('roqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        return view('v1.gempabumi.create',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'datetime_wib' => 'required|date_format:Y-m-d H:i|before:tomorrow',
            'lat_lima' => 'required|numeric|between:-10,12',
            'lat_lima' => 'required|numeric|between:94,142',
            'magnitude' => 'required|numeric|between:0,9',
            'depth' => 'required|numeric|between:9,500',
            'area' => 'required',
            'koter' => 'required',
            'roq_tanggapan' => 'required|boolean',
            'roq_title' => 'required_if:roq_tanggapan,1',
            'roq_intro' => 'required_if:roq_tanggapan,1',
            'roq_konwil' => 'required_if:roq_tanggapan,1',
            'roq_mekanisme' => 'required_if:roq_tanggapan,1',
            'roq_efek' => 'required_if:roq_tanggapan,1',
            'roq_rekom' => 'required_if:roq_tanggapan,1',
            'roq_source' => 'required_if:roq_tanggapan,1|array',
            'roq_source.*' => 'required_if:roq_tanggapan,1|in:BMKG,GFZ,USGS',
            'roq_tsunami' => 'required_if:roq_tanggapan,1|boolean'
        ]);
        return $request;
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
        $roq = MagmaRoq::findOrFail($id);
        return $roq;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
