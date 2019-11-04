<?php

namespace App\Http\Controllers;

use App\MGA\AnggotaKegiatan;
use App\MGA\DetailKegiatan;
use App\User;
use Illuminate\Http\Request;

class AnggotaKegiatanController extends Controller
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
        $users = User::select('name','nip')->orderBy('name')->get();
        $detailKegiatan = DetailKegiatan::with('kegiatan.jenis_kegiatan','ketua','gunungapi')->findOrFail($request->id);
        return view('mga.anggota-kegiatan.create', compact('users','detailKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->has('step') AND $request->step == '2')
        {
            $validated = $this->validate($request,[
                'step' => 'sometimes|in:2',
                'anggota_tim' => 'required|array',
                'anggota_tim.*' => 'required|exists:users,nip'
            ],[
                'step.in' => 'Langkah Selanjutnya tidak dikenal',
                'anggota_tim.array' => 'Format NIP Anggota Tim tidak valid',
                'anggota_tim.*.exists' => 'Anggota Tim tidak ada dalam database',
            ]);

            $users = User::whereIn('nip',$request->anggota_tim)->get();
            $detailKegiatan = DetailKegiatan::with('kegiatan.jenis_kegiatan','ketua','gunungapi','anggota_tim')->findOrFail($request->id);

            return view('mga.anggota-kegiatan.create-periode', compact('users','detailKegiatan'));
        }

        if ($request->has('step') AND $request->step == '3') 
        {
            $validated = $this->validate($request,[
                'step' => 'sometimes|in:3',
                'detail_kegiatan_id' => 'exists:detail_kegiatans,id',
                'kegiatan_id' => 'exists:kegiatans,id',
                'anggota_tim' => 'required|array',
                'start' => 'required|array',
                'end' => 'required|array',
                'uang_harian' => 'required|array',
                'penginapan_tigapuluh' => 'required|array',
                'penginapan_penuh' => 'required|array',
                'jumlah_hari' => 'required|array',
                'uang_transport' => 'required|array',
                'anggota_tim.*' => 'required|exists:users,nip',
                'start.*' => 'required|date_format:Y-m-d',
                'end.*' => 'required|date_format:Y-m-d',
                'uang_harian.*' => 'required|numeric|min:0',
                'penginapan_tigapuluh.*' => 'required|numeric|min:0',
                'penginapan_penuh.*' => 'required|numeric|min:0',
                'jumlah_hari.*' => 'required|numeric|min:0',
                'uang_transport.*' => 'required|numeric|min:0',
            ],[
                'step.in' => 'Langkah Selanjutnya tidak dikenal',
                'anggota_tim.array' => 'Format NIP Anggota Tim tidak valid',
                'start.array' => 'Format Start Date tidak valid',
                'end.array' => 'Format End Date tidak valid',
                'start.*.date_format' => 'Format Start Date tidak valid (YYYY-mm-dd)',
                'end.*.date_format' => 'Format End Date tidak valid (YYYY-mm-dd)',
                'anggota_tim.*.exists' => 'Anggota Tim tidak ada dalam database',
                'uang_harian.*.required' => 'Uang Harian tidak boleh kosong',
                'uang_harian.*.numeric' => 'Uang Harian harus bertipe numerik',
                'uang_harian.*.min' => 'Uang Harian minimal 0 rupiah',
                'penginapan_tigapuluh.*.required' => 'Penginapan 30% tidak boleh kosong',
                'penginapan_tigapuluh.*.numeric' => 'Penginapan 30% harus bertipe numerik',
                'penginapan_tigapuluh.*.min' => 'Penginapan 30% minimal 0 rupiah',
                'penginapan_penuh.*.required' => 'Penginapan 100% tidak boleh kosong',
                'penginapan_penuh.*.numeric' => 'Penginapan 100% harus bertipe numerik',
                'penginapan_penuh.*.min' => 'Penginapan 100% minimal 0 rupiah',
                'jumlah_hari.*.required' => 'Jumlah Hari tidak boleh kosong',
                'jumlah_hari.*.numeric' => 'Jumlah Hari harus bertipe numerik',
                'jumlah_hari.*.min' => 'Jumlah Hari minimal 0 rupiah',
                'uang_transport.*.required' => 'Uang Transport tidak boleh kosong',
                'uang_transport.*.numeric' => 'Uang Transport harus bertipe numerik',
                'uang_transport.*.min' => 'Uang Transport minimal 0 rupiah',
            ]);

            foreach ($request->anggota_tim as $nip_key=> $nip_value) {
                $anggotaKegiatan = AnggotaKegiatan::firstOrCreate(
                    [
                        'detail_kegiatan_id' => $request->detail_kegiatan_id,
                        'nip_anggota' => $request->anggota_tim[$nip_key],
                    ],[
                        'kegiatan_id' => $request->kegiatan_id,
                        'start_date' => $request->start[$nip_key],
                        'end_date' => $request->end[$nip_key],
                        'uang_harian' => $request->uang_harian[$nip_key],
                        'penginapan_tigapuluh' => $request->penginapan_tigapuluh[$nip_key],
                        'penginapan_penuh' => $request->penginapan_penuh[$nip_key],
                        'jumlah_hari_menginap' => $request->jumlah_hari[$nip_key],
                        'uang_transport' => $request->uang_transport[$nip_key],
                        'nip_kortim' => auth()->user()->nip,
                    ]
                );
            }

            return redirect()->route('chambers.administratif.mga.detail-kegiatan.show', $request->detail_kegiatan_id);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MGA\AnggotaKegiatan  $anggotaKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show($nip)
    {
        $anggotas = AnggotaKegiatan::has('kegiatan')->whereNipAnggota($nip)->get();

        if ($anggotas->isEmpty())
            abort(404);

        $anggotas->load('user:nip,name','kegiatan.jenis_kegiatan','detail_kegiatan.gunungapi');

        return view('mga.anggota-kegiatan.show', compact('anggotas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MGA\AnggotaKegiatan  $anggotaKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(AnggotaKegiatan $anggotaKegiatan)
    {
        $anggotaKegiatan = $anggotaKegiatan::with('detail_kegiatan.kegiatan.jenis_kegiatan')
                            ->findOrFail($anggotaKegiatan->id);

        return view('mga.anggota-kegiatan.edit', compact('anggotaKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MGA\AnggotaKegiatan  $anggotaKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnggotaKegiatan $anggotaKegiatan)
    {
        $anggotaKegiatan->start_date = $request->start;
        $anggotaKegiatan->end_date = $request->end;
        $anggotaKegiatan->uang_harian = $request->uang_harian;
        $anggotaKegiatan->penginapan_tigapuluh = $request->penginapan_tigapuluh;
        $anggotaKegiatan->penginapan_penuh = $request->penginapan_penuh;
        $anggotaKegiatan->jumlah_hari_menginap = $request->jumlah_hari;
        $anggotaKegiatan->uang_transport = $request->uang_transport;
        $anggotaKegiatan->nip_kortim = auth()->user()->nip;

        if ($anggotaKegiatan->save())
        {           
            return redirect()->route('chambers.administratif.mga.detail-kegiatan.show', $anggotaKegiatan->detail_kegiatan_id)->with('flash_message','Data Berhasil diperbarui');
        }
        
        return redirect()->route('chambers.administratif.mga.detail-kegiatan.show', $anggotaKegiatan->detail_kegiatan_id)->with('flash_message','Data Gagal diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MGA\AnggotaKegiatan  $anggotaKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(AnggotaKegiatan $anggotaKegiatan)
    {
        if ($anggotaKegiatan->delete()) {
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
