<?php

namespace App\Http\Controllers;

use App\MGA\Kegiatan;
use App\MGA\JenisKegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('role:Super Admin|Kortim MGA');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenisKegiatan = JenisKegiatan::with('bidang')->get();

        if ($jenisKegiatan->isEmpty())
            return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');

        return view('mga.kegiatan.create', compact('jenisKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['unique' => $request->jenis_kegiatan.$request->year]);

        $validated =  $this->validate($request, [
            'jenis_kegiatan' => 'required|exists:jenis_kegiatans,id',
            'unique' => 'required|unique:kegiatans,unique',
            'year' => 'required|date_format:Y',
            'target_jumlah' => 'required|numeric|integer|min:1',
            'target_anggaran' => 'required|numeric|integer'
        ],[
            'jenis_kegiatan.required' => 'Jenis Kegiatan belum dipilih',
            'year.required' => 'Tahun Anggaran tidak boleh kosong',
            'target_jumlah.required' => 'Target Jumlah tidak boleh kosong',
            'target_anggaran.required' => 'Target Anggaran tidak boleh kosong',
            'target_jumlah.min' => 'Target Jumlah Kegiatan minimal 1',
            'jenis_kegiatan.exists' => 'Jenis Kegiatan tidak terdaftar',
            'year.date_format' => 'Format Tahun Anggaran tidak sesuai',
            'unique.unique' => 'Jenis Kegiatan dan Tahun Anggaran sudah ada'
        ]);

        $model = Kegiatan::firstOrCreate(
            [
                'unique' => $request->unique,
            ],
            [
                'jenis_kegiatan_id' => $request->jenis_kegiatan,
                'tahun' => $request->year,
                'target_jumlah' => $request->target_jumlah,
                'target_anggaran' => $request->target_anggaran,
                'nip_kortim' => auth()->user()->nip
            ]);

        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MGA\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(Kegiatan $kegiatan)
    {
        $kegiatan = $kegiatan::with('jenis_kegiatan.bidang','detail_kegiatan.biaya_kegiatan','detail_kegiatan.anggota_tim')
                            ->withCount('detail_kegiatan')
                            ->findOrFail($kegiatan->id);

        return view('mga.kegiatan.show', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MGA\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kegiatan $kegiatan)
    {
        $jenisKegiatan = JenisKegiatan::with('bidang')->get();

        if ($jenisKegiatan->isEmpty())
            return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
            
        return view('mga.kegiatan.edit', compact('kegiatan','jenisKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MGA\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->merge(['unique' => $request->jenis_kegiatan.$request->year]);

        $validated =  $this->validate($request, [
            'jenis_kegiatan' => 'required|exists:jenis_kegiatans,id',
            'unique' => 'required|unique:kegiatans,unique,'.$kegiatan->id,
            'year' => 'required|date_format:Y',
            'target_jumlah' => 'required|numeric|integer|min:1',
            'target_anggaran' => 'required|numeric|integer'
        ],[
            'jenis_kegiatan.required' => 'Jenis Kegiatan belum dipilih',
            'year.required' => 'Tahun Anggaran tidak boleh kosong',
            'target_jumlah.required' => 'Target Jumlah tidak boleh kosong',
            'target_anggaran.required' => 'Target Anggaran tidak boleh kosong',
            'target_jumlah.min' => 'Target Jumlah Kegiatan minimal 1',
            'jenis_kegiatan.exists' => 'Jenis Kegiatan tidak terdaftar',
            'year.date_format' => 'Format Tahun Anggaran tidak sesuai',
            'unique.unique' => 'Jenis Kegiatan dan Tahun Anggaran sudah ada'
        ]);

        $kegiatan->jenis_kegiatan_id = $request->jenis_kegiatan;
        $kegiatan->tahun = $request->year;
        $kegiatan->unique = $request->unique;
        $kegiatan->target_jumlah = $request->target_jumlah;
        $kegiatan->target_anggaran = $request->target_anggaran;
        $kegiatan->save();

        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MGA\Kegiatan  $kegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->delete()) {
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
