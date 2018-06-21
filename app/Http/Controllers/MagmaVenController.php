<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LaporanLetusanRequest;
use App\MagmaVen;
use App\Gadd;
use App\Vona;
use Carbon\Carbon;

use App\Traits\VisualLetusan;

class MagmaVenController extends Controller
{
    use VisualLetusan;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vens = MagmaVen::orderBy('date','desc')
            ->orderBy('time','desc')
            ->paginate(30,['*'],'ven_page');

        return view('gunungapi.letusan.index',compact('vens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        return view('gunungapi.letusan.create',compact('gadds'));
    }

    /**
     * location
     *
     * @param string $type
     * @param float $decimal
     * @return void
     */
    protected function location(string $type,float $decimal)
    {
        $vars = explode(".",$decimal);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];
        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = round($tempma - ($min*60));

        $sym = "N ";
        if ($deg<10 AND $deg>0){
            $deg="0".$deg;
        } else {
            if ($deg<0){
                $deg="0".abs($deg);
                $sym="S ";
            }
        }
        if ($min<10){
            $min="0".$min;
        }
        if ($sec<10){
            $sec="0".$sec;
        }

        if ($type == 'lat')
        {
            return $sym.$deg.' deg '.$min.' min '.$sec.' sec '; 
        }
        return 'E '.$deg.' deg '.$min.' min '.$sec.' sec'; 
    }

    protected function translateDirection($arah)
    {
        $translate = collect($arah)->map(function ($direction) {
            switch ($direction) {
                case 'Timur':
                    $var =  'East';
                    break;
                case 'Tenggara':
                    $var =  'Southeast';
                    break;
                case 'Selatan':
                    $var =  'South';
                    break;
                case 'Barat Daya':
                    $var =  'Southwest';
                    break;
                case 'Barat':
                    $var =  'Barat';
                    break;
                case 'Barat Laut':
                    $var =  'Northwest';
                    break;
                case 'Utara':
                    $var =  'North';
                    break;
                default:
                    $var =  'Northeast';
                    break;
            }
            return $var;
        })->toArray();

        return str_replace_last(', ',' and ', strtolower(implode(', ',$translate)));
    }

    protected function draftVona($ven,$vona)
    {
        $issued = Carbon::createFromFormat('Y-m-d H:i',$ven->date->format('Y-m-d').' '.$ven->time,'Asia/Jakarta')
            ->setTimezone('UTC')
            ->toDateTimeString();

        $location = $this->location('lat',$ven->gunungapi->latitude).$this->location('lon',$ven->gunungapi->longitude);

        $issued_utc = Carbon::createFromFormat('Y-m-d H:i:s',$issued)->format('Hi');
        $issued_lt = Carbon::createFromFormat('Y-m-d H:i',$ven->date->format('Y-m-d').' '.$ven->time,'Asia/Jakarta')->format('Hi');

        if ($ven->visibility == '1') {
            $vas = 'Eruption with volcanic ash cloud at '.$issued_utc.'Z ('.$issued_lt.' LT).';
            $other = 'Ash coud moving to '.$this->translateDirection($ven->arah_asap);
            $height = $ven->height;
        } else {
            $vas = 'Ash cloud is not visibile.';
            $other = null;
            $height = 0;
        }

        $vch_asl = $ven->height+$ven->gunungapi->elevation;

        $vona->ven_uuid = $ven->uuid;
        $vona->issued = $issued;
        $vona->type = 'REAL';
        $vona->code_id = $ven->code_id;
        $vona->cu_code = $vch_asl > 6000 ? 'RED' : 'YELLOW';
        $vona->location = $location;
        $vona->vas = $vas;
        $vona->vch_summit = $height;
        $vona->vch_asl = $vch_asl;
        $vona->vch_other = $other;
        $vona->nip_pelapor = auth()->user()->nip;
        
        if ($vona->save()) {
            return true;
        }

        return false;
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LaporanLetusanRequest $request)
    {
        // return $request;
        $ven = new MagmaVen;
        $vona = new Vona;

        $saveVen = $request->visibility == '1' 
            ? $this->teramati($request,$ven)
            : $this->tidakTeramati($request,$ven);

        $saveVona = $request->draft == '1' ? $this->draftVona($ven,$vona) : false ;

        $uuid = $saveVona != false ? $vona->uuid : '' ;

        if ($saveVen){
            return redirect()->route('chambers.letusan.show',['uuid'=> $ven->uuid]);
        }

        return redirect()->route('chambers.letusan.index')
            ->with('flash_message','Informasi letusan gagal ditambahkan. ');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ven = MagmaVen::findOrFail($id);
        $ven->addPageViewThatExpiresAt(Carbon::now()->addHours(1));
        $visual = $this->visualLetusan($ven);
        return view('gunungapi.letusan.show',compact('ven','visual'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ven = MagmaVen::findOrFail($id);
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        return view('gunungapi.letusan.edit',compact('ven','gadds'));
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
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ven = MagmaVen::findOrFail($id);

        $message = 'Laporan Letusan Gunung '.$ven->gunungapi->name.', tanggal '.$ven->date.' pukul '.$ven->time.' '.$ven->gunungapi->zonearea;

        if($ven->delete()){
            $data = [
                'success' => 1,
                'message' => $message.' berhasil dihapus.'
            ];

            return response()->json($data);
        }
        
        $data = [
            'success' => 1,
            'message' => $message.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
