<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchGunungApi;
use Carbon\Carbon;
use App\Gadd;
use App\MagmaVar;
use App\v1\MagmaVar as OldVar;
use App\VarDaily;
use App\User;
use App\VarPj;
use App\Http\Resources\VarResource;
use App\Http\Resources\VarCollection;

use App\Traits\VisualAsap;
use App\Traits\DeskripsiVar;

class ActivityGaController extends Controller
{
    use VisualAsap,DeskripsiVar;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vars = MagmaVar::orderBy('var_data_date','desc')
                ->orderBy('created_at','desc')
                ->simplePaginate(15);

        $gadds = Gadd::orderBy('name')
                ->whereNotIn('code',['TEO','SBG'])
                ->get();

        return view('gunungapi.laporan.index',compact('vars','gadds'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Set Local Time
        //
        // locale -a
        // sudo locale-gen id_ID.UTF-8
        // sudo dpkg-reconfigure locales

        $var = MagmaVar::with(
                'user.bidang',
                'pj',
                'pos',
                'gunungapi',
                'verifikator',
                'visual',
                'klimatologi',
                'gempa',
                'keterangan')
            ->where('noticenumber',$id)
            ->firstOrFail();

        $gempa = $this->deskripsi($var->gempa)->getGempa();

        $visual = $this->visibility($var->visual->visibility)
                ->asap($var->visual->visual_asap, $var->visual->asap ?? '')
                ->cuaca($var->klimatologi->cuaca)
                ->angin($var->klimatologi->kecangin,$var->klimatologi->arahangin)
                ->suhu($var->klimatologi->suhumin,$var->klimatologi->suhumax)
                ->kelembaban($var->klimatologi->lembabmin,$var->klimatologi->lembabmax)
                ->tekanan($var->klimatologi->tekmin,$var->klimatologi->tekmax)
                ->letusan($var->visual->letusan ?? '')
                ->getVisual();

        $var->addView();        
        $var = new VarResource($var);
        
        return view('gunungapi.laporan.show', compact('var','visual','gempa','pj'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $gadds = Gadd::orderBy('name')
                    ->whereNotIn('code',['TEO','SBG'])
                    ->get();

        $users = User::whereHas('bidang', function($query){
                    $query->where('user_bidang_desc_id','like',2);
                })->orderBy('name')->get();

        $request->flash();

        if (count($request->all()) >0 )
        {

            switch ($request->nip) {
                case 'all':
                    $nip = '%';
                    break;
                default:
                    $nip = $request->input('nip','%');
                    break;
            }

            switch ($request->gunungapi) {
                case 'all':
                    $code = '%';
                    break;
                default:
                    $code = strtoupper($request->input('gunungapi','%'));
                    break;
            }
    
            switch ($request->tipe) {
                case 'all':
                    $periode = '%';
                    break;
                default:
                    $periode = $request->input('tipe','%');
                    break;
            }
    
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
    
            $vars = MagmaVar::where('code_id', 'like', $code)
                        ->whereBetween('var_data_date', [$start, $end])
                        ->where('var_perwkt','like',$periode)
                        ->where('nip_pelapor','like',$nip)
                        ->orderBy('var_data_date','asc')
                        ->orderBy('created_at','desc');
            
            $count = $vars->count();
    
            $vars = $vars->paginate(31);

            return view('gunungapi.laporan.search',compact('input','vars','gadds','users'))->with('flash_result',
            $count.' laporan berhasil ditemukan');
        }

        return view('gunungapi.laporan.search',compact('input','gadds','users'))->with('flash_message',
        'Kriteria pencarian tidak ditemukan/belum ada');

    }

    public function validasi(Request $request)
    {
        $varPj = VarPj::updateOrCreate(
            [
                'noticenumber_id' => $request->noticenumber,
                'nip_id' => auth()->user()->nip
            ],
            [
                
            ]
        );
        
        if($varPj)
        {
            $data = [
                'success' => 1,
                'message' => 'Berhasil Divalidasi'
            ];
    
            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => 'Gagal Verifikasi'
        ];

        return response()->json($data);
    }

    public function verifikasiv1(Request $request)
    {

        $oldVar = OldVar::where('ga_code','like',$request->ga_code)
            ->where('var_noticenumber','like',$request->noticenumber)
            ->first();

        $oldVar->var_nip_pemeriksa_pj = auth()->user()->nip;
        $oldVar->var_nama_pemeriksa_pj = auth()->user()->name;
        
        if ($oldVar->save())
        {
            $data = [
                'success' => 1,
                'message' => 'Berhasil Diverifikasi'
            ];
    
            return response()->json($data);
        }
        
        $data = [
            'success' => 0,
            'message' => 'Gagal Verifikasi'
        ];

        return response()->json($data);
    }
}
