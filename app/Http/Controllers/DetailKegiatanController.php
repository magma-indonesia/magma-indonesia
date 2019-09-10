<?php

namespace App\Http\Controllers;

use App\MGA\DetailKegiatan;
use App\MGA\Kegiatan;
use App\Gadd;
use App\User;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DetailKegiatanController extends Controller
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
        $kegiatan = Kegiatan::with('jenis_kegiatan.bidang')->findOrFail($request->id);
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        $users = User::select('name','nip')->orderBy('name')->get();
        return view('mga.detail-kegiatan.create', compact('kegiatan','gadds','users'));
    }

    protected function deleteProposal($proposal)
    {        
        $delete = Storage::disk('tim-mga')->delete('proposal/'.$proposal);
    }

    protected function uploadProposal($request)
    {

        $kegiatan = Kegiatan::findOrFail($request->kegiatan_id)->jenis_kegiatan->nama;
        $extension = $request->proposal->getClientOriginalExtension();
        $lokasi = $request->code ?: $request->lokasi_lainnya;

        $filename = Str::slug($request->start.' proposal '.$kegiatan.' '.$lokasi, '-');
        $filename = $filename.'.'.$extension;
        
        $store = Storage::disk('tim-mga')->putFileAs(
            'proposal', $request->proposal, $filename
        );

        return $filename;
    }

    protected function deleteLaporan($laporan)
    {       
        return Storage::disk('tim-mga')->delete('laporan/'.$laporan);
    }

    protected function uploadLaporan($request)
    {
        $kegiatan = Kegiatan::findOrFail($request->kegiatan_id)->jenis_kegiatan->nama;
        $extension = $request->laporan_kegiatan->getClientOriginalExtension();
        $lokasi = $request->code ?: $request->lokasi_lainnya;

        $filename = Str::slug(''.$request->start.' laporan '.$kegiatan.' '.$lokasi, '-');
        $filename = $filename.'.'.$extension;
        
        $store = Storage::disk('tim-mga')->putFileAs(
            'laporan', $request->laporan_kegiatan, $filename
        );

        return $filename;
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
            'upah' => 'required|numeric|min:0',
            'bahan' => 'required|numeric|min:1',
            'transportasi' => 'required|numeric|min:1',
            'biaya_lainnya' => 'required|numeric|min:0',
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
            'upah.required' => 'Upah harus diisi',
            'upah.numeric' => 'Upah harus dalam format numeric',
            'upah.min' => 'Upah minimal 0',
            'bahan.required' => 'Biaya Bahan harus diisi',
            'bahan.numeric' => 'Biaya Bahan harus dalam format numeric',
            'bahan.min' => 'Biaya Bahan tidak boleh 0',
            'transportasi.required' => 'Biaya Transportasi harus diisi',
            'transportasi.numeric' => 'Biaya Transportasi harus dalam format numeric',
            'transportasi.min' => 'Biaya Transportasi tidak boleh 0',
            'biaya_lainnya.required' => 'Biaya Bahan Lainnya harus diisi',
            'biaya_lainnya.numeric' => 'Biaya Bahan Lainnya harus dalam format numeric',
        ]);

        $proposal = $request->hasFile('proposal') ? 
            $this->uploadProposal($request) : 
            null;

        $laporan = $request->hasFile('laporan_kegiatan') ? 
            $this->uploadLaporan($request) : 
            null;

        $detail = DetailKegiatan::firstOrCreate(
            [
                'kegiatan_id' => $request->kegiatan_id,
                'code_id' => $request->code,
            ],[
                'lokasi_lainnya' => $request->lokasi_lainnya,
                'start_date' => $request->start,
                'end_date' => $request->end,
                'laporan' => $laporan,
                'proposal' => $proposal,
                'nip_ketua' => $request->ketua_tim,
                'nip_kortim' => auth()->user()->nip
            ]
        );

        $detail->biaya_kegiatan()->firstOrCreate(
            [
                'detail_kegiatan_id' => $detail->id,
            ],[
                'upah' => $request->upah,
                'bahan' => $request->bahan,
                'carter' => $request->transportasi,
                'bahan_lainnya' => $request->biaya_lainnya,
                'nip_kortim' => auth()->user()->nip
            ]
         );

         return redirect()->route('chambers.administratif.mga.kegiatan.show', $request->kegiatan_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(DetailKegiatan $detailKegiatan)
    {
        $detailKegiatan = DetailKegiatan::with(
                                'anggota_tim.user:name,nip','kegiatan.jenis_kegiatan',
                                'ketua','biaya_kegiatan','gunungapi')
                            ->findOrFail($detailKegiatan->id);
                            
        return view('mga.detail-kegiatan.show', compact('detailKegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailKegiatan $detailKegiatan)
    {
        $detailKegiatan = DetailKegiatan::with('kegiatan.jenis_kegiatan.bidang','biaya_kegiatan')->findOrFail($detailKegiatan->id);
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        $users = User::select('name','nip')->orderBy('name')->get();

        return view('mga.detail-kegiatan.edit', compact('detailKegiatan','gadds','users'));
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
        // return $request;

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
            'upah' => 'required|numeric|min:1',
            'bahan' => 'required|numeric|min:1',
            'transportasi' => 'required|numeric|min:1',
            'biaya_lainnya' => 'required|numeric',
            'delete_proposal' => 'sometimes|required|boolean',
            'delete_laporan' => 'sometimes|required|boolean',
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
            'upah.required' => 'Upah harus diisi',
            'upah.numeric' => 'Upah harus dalam format numeric',
            'upah.min' => 'Updah tidak boleh 0',
            'bahan.required' => 'Biaya Bahan harus diisi',
            'bahan.numeric' => 'Biaya Bahan harus dalam format numeric',
            'bahan.min' => 'Biaya Bahan tidak boleh 0',
            'transportasi.required' => 'Biaya Transportasi harus diisi',
            'transportasi.numeric' => 'Biaya Transportasi harus dalam format numeric',
            'transportasi.min' => 'Biaya Transportasi tidak boleh 0',
            'biaya_lainnya.required' => 'Biaya Bahan Lainnya harus diisi',
            'biaya_lainnya.numeric' => 'Biaya Bahan Lainnya harus dalam format numeric',
            'delete_laporan.boolean' => 'Delete Laporan tidak berhasil',
            'delete_proposal.boolean' => 'Delete Proposal tidak berhasil',
        ]);

        $delete_proposal = $request->delete_proposal ? 
            $this->deleteProposal($detailKegiatan->proposal) :
            null;

        $delete_laporan = $request->delete_laporan ? 
            $this->deleteLaporan($detailKegiatan->laporan) :
            null;

        $proposal = $request->hasFile('proposal') ? 
            $this->uploadProposal($request) : 
            null;

        $laporan = $request->hasFile('laporan_kegiatan') ? 
            $this->uploadLaporan($request) : 
            null;

        $detailKegiatan->kegiatan_id = $request->kegiatan_id;
        $detailKegiatan->code_id = $request->code;
        $detailKegiatan->lokasi_lainnya = $request->lokasi_lainnya;
        $detailKegiatan->start_date = $request->start;
        $detailKegiatan->end_date = $request->end;
        $detailKegiatan->proposal = $proposal;
        $detailKegiatan->laporan = $laporan;
        $detailKegiatan->nip_ketua = $request->ketua_tim;
        $detailKegiatan->nip_kortim = auth()->user()->nip;
        $detailKegiatan->save();

        $detailKegiatan->biaya_kegiatan()->updateOrCreate(
            [
                'detail_kegiatan_id' => $detailKegiatan->id
            ],[
                'upah' => $request->upah,
                'bahan' => $request->bahan,
                'carter' => $request->transportasi,
                'bahan_lainnya' => $request->biaya_lainnya,
                'nip_kortim' => auth()->user()->nip,
        ]);

        return redirect()->route('chambers.administratif.mga.kegiatan.show', $detailKegiatan->kegiatan->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailKegiatan $detailKegiatan)
    {
        $delete_proposal = $detailKegiatan->proposal ? 
            $this->deleteProposal($detailKegiatan->proposal) :
            null;

        $delete_laporan = $detailKegiatan->laporan ? 
            $this->deleteLaporan($detailKegiatan->laporan) :
            null;

        if ($detailKegiatan->delete()) {
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

    public function download($id, $type)
    {
        if ($type === 'proposal') {
            $detailKegiatan = DetailKegiatan::whereId($id)->whereNotNull('proposal')->firstOrFail();
            return Storage::disk('tim-mga')->download('proposal/'.$detailKegiatan->proposal);
        }

        else {
            $detailKegiatan = DetailKegiatan::whereId($id)->whereNotNull('laporan')->firstOrFail();
            return Storage::disk('tim-mga')->download('laporan/'.$detailKegiatan->laporan);
        }

    }
}
