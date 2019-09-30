<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\v1\MagmaVar as OldVar;
use App\v1\Gadd;
use App\v1\User;
use App\v1\PosPga;
use App\Http\Requests\v1\CreateVar;
use App\Http\Requests\v1\CreateVarRekomendasi;
use App\Http\Requests\v1\CreateVarVisual;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;
use App\Traits\JenisGempaVar;

class MagmaVarController extends Controller
{
    use JenisGempaVar,VisualAsap,DeskripsiGempa;

    /**
     * Properti untuk request (bukan session)
     *
     * @param \Illuminate\Http\Request $request
     */
    protected $request;

    /**
     * Set Variable session Visual untuk memeriksa foto visual temporary 
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Delete temporary photo sebelum finisihing upload
     *
     * @return void
     */
    protected function deletePhoto()
    {
        $ga_code = session('old_var')['ga_code'];
        $noticenumber = session('old_var')['var_noticenumber'];
        $filename = session('old_var_visual')['foto'];

        $path = 'img/ga/'.$ga_code.'/'.$filename;

        $this->request->foto || $this->request->hasfoto == 0 ?
                    Storage::disk('temp')->delete('var/'.$filename) :
                    false;
        
        return $this;
    }

    /**
     * Updating file foto visual
     *
     * @return String $filename
     */
    protected function updatePhoto()
    {
        $ga_code = session('old_var')['ga_code'];
        $noticenumber = session('old_var')['var_noticenumber'];
        $extension = '.'.$this->request->foto->getClientOriginalExtension();
        $name = $ga_code.$noticenumber.$extension;

        $path = 'img/ga/'.$ga_code;

        if ($this->request->hasfoto == 0)
            return null;

        if ($this->request->has('foto')) {
            $filename = $this->request->hasfoto == '1' ?
                            $name :
                            session('old_var_visual')['foto'];

            $upload = $this->request->hasfoto == '1' ?
                        $this->request->foto->storeAs('var',$filename ,'temp') :
                        null;

            
            $this->var_image = $upload ? 
                        'https://magma.vsi.esdm.go.id/'.$path.'/'.$filename :
                        'https://magma.vsi.esdm.go.id/img/ga/IBU/IBU_20190503060512.png';

            return $filename;

        }

        return session('old_var_visual')['foto'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = OldVar::select('no')->orderBy('no','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $vars = Cache::remember('v1/vars-'.$last->no.'-page-'.$page, 30, function () {
            return OldVar::select(
                'no','var_data_date','periode',
                'ga_nama_gapi','cu_status','var_nama_pelapor')
                ->orderBy('var_data_date','desc')
                ->orderBy('periode','desc')
                ->paginate(30);
        });

        return view('v1.gunungapi.laporan.index',compact('vars'));
    }

    /**
     * Display a filtering listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function filter(Request $request)
    {
        $gadds = Cache::remember('v1/gadds', 120, function () {
            return Gadd::select('no','ga_code','ga_nama_gapi')
                ->whereNotIn('ga_code',['TEO','SBG'])
                ->orderBy('ga_nama_gapi','asc')
                ->get();
        });
        
        $users = Cache::remember('v1/users-mga', 120, function () {
            return User::select('id','vg_nip','vg_nama')
                ->where('vg_bid','Pengamatan dan Penyelidikan Gunungapi')
                ->orderBy('vg_nama','asc')
                ->get();
        });

        if (count($request->all()))
        {
            switch ($request->tipe) {
                case 'all':
                    $periode = '%';
                    break;
                case '0':
                    $periode = '00:00-24:00';
                    break;
                case '1':
                    $periode = '00:00-06:00';
                    break;
                case '2':
                    $periode = '06:00-12:00';
                    break;
                case '3':
                    $periode = '12:00-18:00';
                    break;
                default:
                    $periode = '18:00-24:00';
                    break;
            }

            $nip = $request->nip == 'all' ? '%' : $request->input('nip','%');
            $code = $request->gunungapi == 'all' ? '%' : strtoupper($request->input('gunungapi','%'));
            $bulan = $request->input('bulan', Carbon::parse('first day of January')->format('Y-m-d'));        
            $start = $request->input('start', Carbon::parse('first day of January')->format('Y-m-d'));
            $end = $request->input('end', Carbon::now()->format('Y-m-d'));

            switch ($request->jenis) {
                case '0':
                    $end = Carbon::createFromFormat('Y-m-d',$start)->addDays(13)->format('Y-m-d');
                    break;
                case '1':
                    $start = Carbon::createFromFormat('Y-m-d',$bulan)->startOfMonth()->format('Y-m-d');
                    $end = Carbon::createFromFormat('Y-m-d',$bulan)->endOfMonth()->format('Y-m-d');
                    break;
                default:
                    $end = $end;
                    break;
            }

            $vars = OldVar::select('no','ga_nama_gapi','var_data_date','periode','var_perwkt','periode','var_nama_pelapor')
                        ->where('ga_code', 'like', $code)
                        ->whereBetween('var_data_date', [$start, $end])
                        ->where('periode','like',$periode)
                        ->where('var_nip_pelapor','like',$nip)
                        ->orderBy('var_data_date','asc')
                        ->orderBy('var_data_date','desc');
            
            $count = $vars->count();
    
            $vars = $vars->paginate(31);

            return view('v1.gunungapi.laporan.filter',compact('vars','gadds','users'))->with('flash_result',
            $count.' laporan berhasil ditemukan');
        }

        return view('v1.gunungapi.laporan.filter',compact('gadds','users'))->with('flash_message',
        'Kriteria pencarian tidak ditemukan/belum ada');
    }

    protected function getResultFilterGempa($request)
    {
        $code = $request->gunungapi == 'all' ? '%' : strtoupper($request->input('gunungapi','%'));
        $gempas = $request->gempa;

        $query = OldVar::select('ga_code','ga_nama_gapi','var_data_date')
                    ->where('ga_code','like',$code)
                    ->whereBetween('var_data_date',[$request->start,$request->end]);

        foreach ($gempas as $gempa)
        {
            $var_gempa = 'var_'.$gempa;
            $raw = 'SUM('.$var_gempa.') as jumlah_'.$var_gempa;
            $query = $query->selectRaw($var_gempa);
            $query = $query->selectRaw($raw);
        }

        return $query->groupBy('magma_var.ga_nama_gapi')->get();
    }

    protected function transfromFiltered($result)
    {

    }

    /**
     * Display a filtering listing of the resource based on earthquake.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function filterGempa(Request $request)
    {
        $gadds = Cache::remember('v1/gadds', 120, function () {
            return Gadd::select('no','ga_code','ga_nama_gapi')
                ->whereNotIn('ga_code',['TEO','SBG'])
                ->orderBy('ga_nama_gapi','asc')
                ->get();
        });

        $jenis_gempa = collect($this->jenisgempa())->chunk(10);

        if (count($request->all()))
        {
            $validated = $request->validate([
                'gunungapi' => 'required',
                'gempa' => 'required|array'
            ]);

            $result = $this->getResultFilterGempa($request);
            $transformed = $this->transfromFiltered($result);

            return $result;
        }

        return view('v1.gunungapi.laporan.filter-gempa',compact('gadds','jenis_gempa'))->with('flash_message',
        'Kriteria pencarian tidak ditemukan/belum ada');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createVar()
    {
        $var = session('old_var');
        $pgas = Cache::remember('v1/pgas', 360, function () {
            return PosPga::whereNotIn('code_id',['BTK','PVG'])
                    ->orderBy('code_id')
                    ->get();
        });

        return view('v1.gunungapi.laporan.create', compact('var','pgas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\v1\CreateVar $request
     * @return \Illuminate\Http\Response
     */
    public function storeVar(CreateVar $request)
    {
        $draft = $this->setVarSession($request);
        return redirect()->route('chambers.v1.gunungapi.laporan.create.rekomendasi');
    }

    /**
     * Set session untuk variable VAR
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVar $var
     * @return void
     */
    public function setVarSession($request)
    {
        try {
            $ga_code = substr($request->code,0,3);
            $gadd = Gadd::where('ga_code',$ga_code)->firstOrFail();
            $pre_var = OldVar::select('ga_code','pre_status','var_rekom')
                        ->where('ga_code',$ga_code)
                        ->orderBy('var_data_date','desc')
                        ->first();

            switch ($pre_var->pre_status) {
                case '1':
                    $pre_status = 'Level I (Normal)';
                    break;
                case '2':
                    $pre_status = 'Level II (Waspada)';
                    break;
                case '3':
                    $pre_status = 'Level III (Siaga)';
                    break;
                default:
                    $pre_status = 'Level IV (Awas)';
                    break;
            }
            
            $var_perwkt = $request->date == '00:00-24:00' ? '24 Jam' : '6 Jam';

            $var = [
                'code' => $request->code,
                'var_issued' => now()->format('Y/m/d H:i:s'),
                'ga_code' => $ga_code,
                'var_noticenumber' => $request->noticenumber,
                'ga_nama_gapi' => $gadd->ga_nama_gapi,
                'ga_id_smithsonian' => $gadd->ga_id_smithsonian,
                'cu_status' => $request->status,
                'pre_status' => $pre_status,
                'var_source' => 'Pos Pengamatan Gunungapi '.$gadd->ga_nama_gapi,
                'volcano_location' => $gadd->ga_lat_gapi.', '.$gadd->ga_lon_gapi,
                'area' => $gadd->ga_kab_gapi.', '.$gadd->ga_prov_gapi,
                'summit_elevation' => $gadd->ga_elev_gapi,
                'var_perwkt' => $var_perwkt,
                'periode' => $request->periode,
                'var_data_date' => $request->date,
                'var_rekom' => $pre_var->var_rekom,
                'var_nip_pelapor' => auth()->user()->nip,
                'var_nama_pelapor' => auth()->user()->name,
            ];

            session(['old_var' => $var]);

            return $var;
        }

        catch (Exception $e)
        {
            return back()->withError('Error Create VAR');
        }
   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createRekomendasi()
    {
        $rekomendasi = session('old_var')['var_rekom'];
        return view('v1.gunungapi.laporan.create-rekomendasi',compact('rekomendasi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\v1\CreateVar $request
     * @return \Illuminate\Http\Response
     */
    public function storeRekomendasi(CreateVarRekomendasi $request)
    {
        if (empty(session('old_var')))
            return redirect()->route('chambers.v1.gunungapi.laporan.create.var');

        $request->rekomendasi == 9999 ? 
                        session()->put('var.var_rekom',$request->rekomendasi_text) : '';

        return redirect()->route('chambers.v1.gunungapi.laporan.create.visual');
    }

    /**
     * Create laporan MAGMA-VAR
     * Meliputi data dasar Visual laporan
     *
     * @return \Illuminate\Http\Response
     */
    public function createVisual(Request $request)
    {
        if (empty(session('old_var')))
            return redirect()->route('chambers.v1.gunungapi.laporan.create.var');

        if (empty(session('old_var')['var_rekom']))
            return redirect()->route('chambers.v1.gunungapi.laporan.create.rekomendasi');

        $visual = session('old_var_visual');

        return view('v1.gunungapi.laporan.create-visual',compact('visual'));
    }

    /**
     * Store session for Visual Information
     *
     * @param \App\Http\Requests\v1\CreateVarVisual $request
     * @return \Illuminate\Http\Response
     */
    public function storeVarVisual(CreateVarVisual $request)
    {
        $this->setVarVisualSession($request);
        return redirect()->route('chambers.v1.gunungapi.laporan.create.klimatologi');
    }

    /**
     * Set session untuk variable Var Visual
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarVisualSession($request)
    {
        try {
            $visual = [
                'hasfoto' => $request->hasfoto,
                'foto' => $this->setRequest($request)->deletePhoto()->updatePhoto(),
                'var_image' => $this->var_image,
                'var_image_create' => 'Taken '.now()->format('Y-m-d H:i:s').' WIB (UTC +7)',
                'var_visibility' => $request->visibility,
                'var_asap' => $request->visual_asap,
                'var_tasap_min' => $request->tasap_min,
                'var_tasap' => $request->tasap_max,
                'var_wasap' => $request->wasap,
                'var_intasap' => $request->intasap,
                'var_tekasap' => $request->tekasap,
                'var_viskawah' => $request->visual_kawah,
                'var_ketlain' => $request->ketlain
            ];

            session(['old_var_visual' => $visual]);

            return $visual;
        }

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Create laporan MAGMA-VAR
     * Input data-data klimatologi Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function createKlimatologi(Request $request)
    {
        if (empty(session('old_var')))
            return redirect()->route('chambers.v1.gunungapi.laporan.create.var');

        if (empty(session('old_var')['var_rekom']))
            return redirect()->route('chambers.v1.gunungapi.laporan.create.rekomendasi');

        if (empty(session('old_var_visual')))
            return redirect()->route('chambers.v1.gunungapi.laporan.create.visual');

        $klimatologi = session('old_var_klimatologi');

        return view('v1.gunungapi.laporan.create-klimatologi',compact('klimatologi'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $var = Cache::remember('v1/var-show-'.$id, 60, function () use($id) {
            return OldVar::findOrFail($id); 
        });

        $others = Cache::remember('v1/var-show-others-'.$id, 60, function () use($var) {
            return OldVar::select('no','ga_code','var_data_date','periode','cu_status')
                    ->where('ga_code',$var->ga_code)
                    ->where('var_data_date','like',$var->var_data_date->format('Y-m-d'))
                    ->whereIn('periode',['00:00-06:00','06:00-12:00','12:00-18:00','18:00-24:00','00:00-24:00'])
                    ->get();
        });
        
        $asap = (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap,
        ];

        $visual = Cache::remember('v1/var-show-visual-'.$id, 60, function () use($var,$asap) {
            return $this->visibility($var->var_visibility->toArray())
                    ->asap($var->var_asap, $asap)
                    ->cuaca($var->var_cuaca->toArray())
                    ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                    ->getVisual();
        });
        
        $gempa = Cache::remember('v1/var-show-gempa-'.$id, 60, function () use($var) {
            return $this->getDeskripsiGempa($var);
        });

        return view('v1.gunungapi.laporan.show',compact('var','others','visual','gempa'));
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

    /**
     * Check existing VAR
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */    
    public function exists(Request $request)
    {
        $ga_code = substr($request->code,0,3);
        $var = OldVar::select(
                'no','ga_code','var_data_date',
                'periode','var_perwkt')
                ->where('ga_code',$ga_code)
                ->where('var_data_date',$request->date)
                ->orderBy('var_log')
                ->get();

        return response()->json($var);
    }
}
