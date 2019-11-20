<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaRoq;
use App\Exports\v1\RoqsExport;
use App\Http\Requests\v1\CreateRoqRequest;

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
            [
                'except' => ['index','show']
            ]
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
     * @param  \App\Http\Requests\v1\CreateRoqRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoqRequest $request)
    {
        $roq = MagmaRoq::updateOrCreate(
            [
                'id_lap' => $request->id_lap
            ],
            [
                'datetime_wib' => $request->datetime_wib,
                'datetime_wib_str' => $request->datetime_wib_str,
                'datetime_utc' => $request->datetime_utc,
                'magnitude' => $request->magnitude,
                'magtype' => $request->magtype,
                'depth' => $request->depth,
                'dep_unit' => $request->dep_unit,
                'lon_lima' => $request->lon_lima,
                'lat_lima' => $request->lat_lima,
                'latlon_text'  => $request->latlon_text,
                'area' => $request->area,
                'koter'  => $request->koter,
                'nearest_volcano' => $request->nearest_volcano,
                'mmi' => empty($request->mmi) ? null : $request->mmi,
                'nearest_volcano' => $request->nearest_volcano,
                'roq_tanggapan' => $request->roq_tanggapan ? 'YA' : 'TIDAK',
                'roq_title' => $request->roq_title,
                'roq_tsu' => $request->roq_tsu ? 'YA' : 'TIDAK',
                'roq_intro' => $request->roq_intro,
                'roq_konwil' => $request->roq_konwil,
                'roq_mekanisme' => $request->roq_mekanisme,
                'roq_efek' => $request->roq_efek,
                'roq_rekom' => $request->roq_rekom,
                'roq_source' => $request->roq_source,
                'roq_nama_pelapor' => $request->roq_nama_pelapor,
                'roq_nip_pelapor' => $request->roq_nip_pelapor,
                'roq_nama_pemeriksa' => $request->roq_nama_pemeriksa,
                'roq_nip_pemeriksa' => $request->roq_nip_pemeriksa,
            ]
        );

        $messages = $roq ? 
                        'Kejadian Gempa Bumi berhasil ditambahkan!' :
                        'Kejadian Gempa Bumi gagal ditambahkan!' ;

        return redirect()->route('chambers.v1.gempabumi.index')
                    ->with('flash_message',$messages);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roq = MagmaRoq::where('no',$id)
                ->where('roq_tanggapan','like','YA')
                ->firstOrFail();
        return view('v1.gempabumi.show', compact('roq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        $roq = MagmaRoq::findOrFail($id);

        return view('v1.gempabumi.edit', compact('roq','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRoqRequest $request, $id)
    {
        $roq = MagmaRoq::findOrFail($id);
        $roq = $roq->update(
            [
                'datetime_wib' => $request->datetime_wib,
                'datetime_wib_str' => $request->datetime_wib_str,
                'datetime_utc' => $request->datetime_utc,
                'magnitude' => $request->magnitude,
                'magtype' => $request->magtype,
                'depth' => $request->depth,
                'dep_unit' => $request->dep_unit,
                'lon_lima' => $request->lon_lima,
                'lat_lima' => $request->lat_lima,
                'latlon_text'  => $request->latlon_text,
                'area' => $request->area,
                'koter'  => $request->koter,
                'mmi' => empty($request->mmi) ? null : $request->mmi,
                'nearest_volcano' => $request->nearest_volcano,
                'roq_tanggapan' => $request->roq_tanggapan ? 'YA' : 'TIDAK',
                'roq_title' => $request->roq_title,
                'roq_tsu' => $request->roq_tsu ? 'YA' : 'TIDAK',
                'roq_intro' => $request->roq_intro,
                'roq_konwil' => $request->roq_konwil,
                'roq_mekanisme' => $request->roq_mekanisme,
                'roq_efek' => $request->roq_efek,
                'roq_rekom' => $request->roq_rekom,
                'roq_source' => $request->roq_source,
                'roq_nama_pelapor' => $request->roq_nama_pelapor,
                'roq_nip_pelapor' => $request->roq_nip_pelapor,
                'roq_nama_pemeriksa' => $request->roq_nama_pemeriksa,
                'roq_nip_pemeriksa' => $request->roq_nip_pemeriksa,
            ]
        );

        $messages = $roq ? 
                        'Kejadian Gempa Bumi berhasil ditambahkan!' :
                        'Kejadian Gempa Bumi gagal ditambahkan!' ;

        return redirect()->route('chambers.v1.gempabumi.index')
                    ->with('flash_message',$messages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $roq = MagmaRoq::findOrFail($id);

        if ($roq->delete())
        {
            $data = [
                'success' => 1,
                'message' => 'Kejadian Gempa Bumi berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => 'Gagal dihapus.'
        ];

        return response()->json($data);
    }

    /**
     * Apply Filter laporan
     *
     * @param [type] $request
     * @return void
     */
    protected function applyFilter($request)
    {
        $roqs = MagmaRoq::whereBetween('datetime_wib',[$request->start, $request->end])
                    ->where('magnitude','>=',$request->magnitudo)
                    ->where('roq_tanggapan','like',$request->tanggapan ? 'YA' : '%')
                    ->where('roq_nip_pelapor','like',$request->nip != 'all' ? $request->nip : '%')
                    ->orderBy('datetime_wib');

        $roqs = $request->form == 'download' ? 
                    $roqs->get() :
                    $roqs->select('no','datetime_wib','magnitude','roq_tanggapan','roq_nip_pelapor','roq_nama_pelapor','latlon_text','area','depth')->get(); 

        return $roqs;
    }

    /**
     * Filter Laporan
     *
     * @param Request $request
     * @return void
     */
    public function filter(Request $request)
    {
        $roqs = MagmaRoq::has('user')
                    ->with('user:vg_nip,vg_nama')
                    ->select('roq_nip_pelapor')
                    ->groupBy('roq_nip_pelapor')
                    ->get();

        $filtereds = count($request->all()) ? $this->applyFilter($request) : collect([]);

        if ($request->isMethod('post') AND $filtereds->isEmpty()) 
            return back()->with('flash_filter','Hasil pencarian tidak ditemukan');

        if ($request->form == 'download')
            return $this->export($request,$filtereds);

        return view('v1.gempabumi.filter', compact('roqs','filtereds','request'));
    }

    /**
     * Export Data tanggapan gempa bumi
     *
     * @param Request $request
     * @return void
     */
    public function export(Request $request, $filtereds)
    {
        return new RoqsExport($request, $filtereds);
    }
}
