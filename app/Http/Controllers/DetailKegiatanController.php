<?php

namespace App\Http\Controllers;

use App\MGA\DetailKegiatan;
use App\MGA\Kegiatan;
use App\Gadd;
use App\User;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DetailKegiatanController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('role:Super Admin,Kortim MGA');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $kegiatan = Kegiatan::findOrFail($request->id);
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        $users = User::select('name','nip')->orderBy('name')->get();
        return view('mga.detail-kegiatan.create', compact('kegiatan','gadds','users'));
    }

    protected function uploadProposal($request)
    {
        $kegiatan = Kegiatan::findOrFail($request->kegiatan_id)->jenis_kegiatan->nama;
        $extension = $request->proposal->getClientOriginalExtension();
        $lokasi = $request->code ?: $request->lokasi_lainnya;

        $filename = Str::slug($request->start.' '.$kegiatan.' '.$lokasi, '-');
        
        $store = Storage::disk('tim-mga')->putFileAs(
            'proposal', $request->proposal, $filename.'.'.$extension
        );

        return $store;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $code = $request->code === null ?
            'nullable' :
            'exists:ga_dd,code';

        $validated = $this->validate($request, [
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'code' => $code,
            'lokasi_lainnya' => 'required_if:code,',
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
            'ketua_tim' => 'required|exists:users,nip',
            'proposal' => 'nullable|mimes:doc,docx,pdf|max:32000',
            'laporan_kegiatan' => 'nullable|mimes:doc,docx,pdf|max:32000',
        ],[
            'kegiatan_id.required' => 'Nama Kegiatan belum dipilih',
            'kegiatan_id.exists' => 'Nama Kegiatan tidak ada dalam daftar',
            'code.exists' => 'Gunung Api tidak ada dalam database',
            'lokasi_lainnya.required_if' => 'Lokasi Lainnya harus diisi jika lokasi bukan di daerah Gunung Api',
            'start.required' => 'Tanggal Berangkat belum diisi',
            'start.date_format' => 'Format tanggal tidak sesuai (YYYY-mm-dd)',
            'end.required' => 'Tanggal Pulang belum diisi',
            'end.date_format' => 'Format tanggal tidak sesuai (YYYY-mm-dd)',
            'ketua_tim.required' => 'Ketua Tim belum dipilih',
            'ketua_tim.exists' => 'Ketua Tim tidak ada dalam data pegawai',
            'proposal.mimes' => 'Format Proposal yang didukung doc, docx dan PDF',
            'proposal.max' => 'Ukuran Proposal maksimal 32MB',
            'laporan_kegiatan.mimes' => 'Format Laporan yang didukung doc, docx dan PDF',
        ]);

        if ($request->hasFile('proposal'))
            $this->uploadProposal($request);

        // $store = Storage::disk('tim-mga')->putFileAs('/', $request->proposal, 'anto.docx');
        
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(DetailKegiatan $detailKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailKegiatan $detailKegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailKegiatan $detailKegiatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailKegiatan $detailKegiatan)
    {
        //
    }
}
