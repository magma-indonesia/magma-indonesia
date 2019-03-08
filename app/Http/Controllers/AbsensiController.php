<?php

namespace App\Http\Controllers;

use App\Absensi;
use App\User;
use App\v1\Absensi as OldAbsensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Http\Requests\SearchAbsensiRequest;

class AbsensiController extends Controller
{

    /**
     * Absensi variable
     *
     * @var array
     */
   protected $absensis;

    /**
     * Set variable Absensi
     *
     * @param  \App\Http\Requests\SearchAbsensiRequest  $request
     * @return $this;
     */
    protected function setAbsensis($request)
    {
        if ($request->has(['nip','start','end'])) {
            $range = array();
            $absensis = Absensi::where('nip_id',$request->nip)
                            ->whereBetween('checkin',[$request->start,$request->end])
                            ->get();

            $grouped = $absensis->groupBy(function ($item,$key) {
                return $item['checkin']->format('Y-m-d');
            });

            $period = CarbonPeriod::create($request->start, $request->end);
            foreach($period as $date) {
                $range[$date->format('Y-m-d')] = array();
            }
            
            $this->absensis = collect($range)->merge($grouped);
            return $this;
        }

        $this->absensis = array();
        return $this;
    }

    /**
     * Get absensi variable
     *
     * @return array
     */
    protected function getAbsensis()
    {
        return $this->absensis;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchAbsensiRequest $request)
    {
        $users = User::select('nip','name')->orderBy('name')->get();
        $data = User::select('nip','name')->where('nip',$request->nip)->first();
        $absensis = $this->setAbsensis($request)->getAbsensis();

        return view('absensi.search',compact('users','data','absensis'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d'
        ]);

        $date = !$request->has('date') ?
                    now()->format('Y-m-d') :
                    $request->date;

        $users = User::select('nip','name')
                ->whereHas('absensi', function($query) use ($date) {
                    $query->where('checkin','like',$date.'%');
                })
                ->with(['latest_absensi' => function($query) use ($date) {
                    $query->where('checkin','like',$date.'%')
                        ->with('kantor.pos_pga.gunungapi:code,name')
                        ->orderBy('duration','desc');
                }])
                ->get();

        $grouped = $users->sortBy(function ($item, $key) {
            return $item['latest_absensi']['kantor']['nama'];
        })->values();

        $users = User::select('nip','name')->orderBy('name')->get();

        return view('absensi.index',['absensis' => $grouped, 'date' => $date, 'users' => $users]);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 'store';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User::id $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $nip)
    {
        $request->validate([
            'year' => 'nullable|date_format:Y',
            'month' => 'nullable|date_format:m',
            'day' => 'nullable|date_format:d',
        ]);

        $user = User::where('nip',$nip)->firstOrFail();
        $kantor = $user->administrasi->kantor;

        $absensi = $user->absensi()
                    ->whereBetween('checkin',[now()->format('Y').'-01-01 00:00:00',now()->format('Y-m-d H:i:s')])
                    ->get();

        $jumlah = [
            'total' => $absensi->count(),
            'alpha' => $absensi->where('keterangan',0)->count(),
            'hadir' => $absensi->where('keterangan',1)->count(),
            'libur' => $absensi->where('keterangan',2)->count(),
            'izin' => $absensi->where('keterangan',3)->count(),
            'sakit' => $absensi->where('keterangan',4)->count(),
            'cuti' => $absensi->where('keterangan',10)->count(),
            'tugas_belajar' => $absensi->where('keterangan',6)->count(),
            'dinas_luar' => $absensi->where('keterangan',7)->count(),
        ];

        $durasi = round($absensi->sum('duration')/60);

        $period = CarbonPeriod::create(now()->format('Y').'-01-01', now()->format('Y-m-d'));
        foreach($period as $date) {
            $range[$date->format('Y-m-d')] = array();
        }
        
        $absensi = $absensi->groupBy(function ($item,$key) {
            return \Carbon\Carbon::parse($item->checkin)->format('Y-m-d');
        });

        $absensi = collect($range)->sortByDesc(function ($item,$key) {
            return $key;
        })->merge($absensi);
                        
        return view('absensi.show',compact('user','kantor','absensi','jumlah','durasi'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\v1\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}
