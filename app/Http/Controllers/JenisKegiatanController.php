<?php

namespace App\Http\Controllers;

use App\MGA\JenisKegiatan;
use App\MGA\Kegiatan;
use App\UserBidang as Bidang;
use Illuminate\Http\Request;

class JenisKegiatanController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('role:Super Admin|Kortim MGA');
        $this->middleware('role:Super Admin')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenis = JenisKegiatan::with('bidang')->withCount('detail_kegiatan')->get();
        $kegiatans = Kegiatan::with('jenis_kegiatan.bidang','biaya_kegiatan','kortim')
                        ->withCount('detail_kegiatan')
                        ->orderBy('tahun','desc')
                        ->get();
        return view('mga.jenis-kegiatan.index', compact('jenis','kegiatans','bidang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bidangs = Bidang::whereNotIn('code',['pvg'])->get();
        return view('mga.jenis-kegiatan.create', compact('bidangs'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated =  $this->validate($request, [
            'name' => 'required|array',
            'name.0' => 'required|unique:jenis_kegiatans,nama',
            'name.1' => 'nullable|unique:jenis_kegiatans,nama',
            'name.2' => 'nullable|unique:jenis_kegiatans,nama',
            'name.3' => 'nullable|unique:jenis_kegiatans,nama',
            'bidang' => 'required|exists:user_bidangs,code',
        ]);

        foreach ($request->name as $name) {

            if ($name) {
                $district = JenisKegiatan::firstOrCreate(
                    [
                        'nama' => ucwords($name),
                    ],
                    [
                        'code' => $request->bidang,
                    ]);
            }
   
        }

        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MGA\JenisKegiatan  $jenisKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MGA\JenisKegiatan  $jenisKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisKegiatan $jenisKegiatan)
    {
        $bidangs = Bidang::whereNotIn('code',['pvg'])->get();
        return view('mga.jenis-kegiatan.edit', compact('jenisKegiatan','bidangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MGA\JenisKegiatan  $jenisKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisKegiatan $jenisKegiatan)
    {
        $validated =  $this->validate($request, [
            'name' => 'required|string|unique:jenis_kegiatans,nama,NULL,id,created_at,NULL',
            'bidang' => 'required|exists:user_bidangs,code',
        ]);

        $jenisKegiatan->nama = ucwords($request->name);
        $jenisKegiatan->code = $request->bidang;
        $jenisKegiatan->save();

        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MGA\JenisKegiatan  $jenisKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisKegiatan $jenisKegiatan)
    {
        if ($jenisKegiatan->delete()) {
            $data = [
                'success' => 1,
                'message' => 'Data berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => 'Gagal dihapus.'
        ];

        return response()->json($data);
    }
}
