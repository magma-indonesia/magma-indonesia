<?php

namespace App\Http\Controllers;

use App\KameraGunungApi;
use App\Gadd;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class KameraGunungApiController extends Controller
{

    protected function getUrl($cctv)
    {
        return 'https://'.config('app.cctv_url').$cctv->url;
    }

    protected function getImage()
    {
        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cctvs = KameraGunungApi::orderBy('hit','desc')->with('gunungapi')->get();
        return view('gunungapi.cctv.index',compact('cctvs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::orderBy('name')->get();
        return view('gunungapi.cctv.create',compact('gadds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cctv = KameraGunungApi::create([
            'code' => $request->code,
            'lokasi' => $request->lokasi,
            'url' => $request->url,
            'elevation' => $request->elevation,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'publish' => $request->published,
        ]);

        return redirect()->route('chambers.cctv.index')->with('flash_message','CCTV berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $cctv = KameraGunungApi::where('uuid',$uuid)->firstOrFail();
        $url = $this->getUrl($cctv);
        $image = Image::make($url);

        return '<img src="'.$image->encode('data-url').'">';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cctv = KameraGunungApi::findOrFail($id);
        $gadds = Gadd::orderBy('name')->get();
        return view('gunungapi.cctv.edit',compact('gadds','cctv'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KameraGunungApi  $kameraGunungApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cctv = KameraGunungApi::findOrFail($id);
        $cctv->update(
            [
                'code' => $request->code,
                'lokasi' => $request->lokasi,
                'url' => $request->url,
                'elevation' => $request->elevation,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'publish' => $request->published,
            ]
        );

        return redirect()->route('chambers.cctv.index')->with('flash_message','CCTV berhasil dirubah');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KameraGunungApi  $kameraGunungApi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cctv = KameraGunungApi::findOrFail($id);
        if ($cctv->delete())
        {
            return response()->json([
                'success' => 1,
                'message' => 'Berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Gagal dihapus'
        ]);
    }
}
