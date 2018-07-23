<?php

namespace App\Http\Controllers;

use App\Vona;
use App\VonaSubscriber as Subscription;
use App\Gadd;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VonaController extends Controller
{
    /**
     * Convert longitude and latitude to decimal text
     *
     * @return lat,lon string
     */
    protected function location($type,$decimal)
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $vonas = Vona::select('uuid','issued','cu_code','prev_code','vch_asl','code_id','nip_pelapor')
                ->orderBy('issued','desc')
                ->where('sent',1)
                ->paginate(30,['*'],'vona_page');

        return view('vona.index',compact('vonas'));
    }

    /**
     * Display a search result of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {        
        $q = $request->q;
        // $vonas = Vona::orderBy('issued','desc')->where('sent',1)->paginate(30,['*'],'vona_page');

        return view('vona.search',compact('q'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function draft(Request $request)
    {        
        $vonas = Vona::orderBy('issued','desc')
                ->where('sent',0)
                ->paginate(30,['*'],'vona_page');

        return view('vona.draft',compact('vonas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::select('name','code')->orderBy('name')->get();

        $users = User::whereHas('bidang', function($query){
            $query->where('bidang_id','like',2);
        })->orderBy('name')->get();
        
        return view('vona.create',compact('gadds','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nip = $request->input('nip',auth()->user()->nip);

        $request->merge([
            'nip' => $nip,
        ]);

        $this->validate($request, [
            'nip' => 'required|digits_between:16,18',
            'code' => 'required|size:3',
            'tipe' => 'required|in:real,exercise',
            'cucode' => 'required|in:green,yellow,orange,red',
            'vas' => 'required|min:50',
            'vch' => 'required|max:5',
            'ovci' => 'required|min:20',
            'remarks' => 'nullable'
        ]);

        $user = User::where('nip','like',$nip)->firstOrFail();
        $gadd = Gadd::where('code',$request->code)->firstOrFail();

        $location = $this->location('lat',$gadd->latitude).$this->location('lon',$gadd->longitude);

        $issued = now('UTC')->toDateTimeString();
        $tipe = strtoupper($request->input('tipe','real'));
        $code = $request->code;
        $cucode = strtoupper($request->cucode);
        $vas = $request->vas;
        $vch = $request->vch;
        $ovci = $request->ovci;
        $remarks = $request->remarks;

        $vona = Vona::create([
            'issued' => $issued,
            'type' => $tipe,
            'code_id' => $code,
            'cu_code' => $cucode,
            'location' => $location,
            'vas' => $vas,
            'vch_summit' => $vch,
            'vch_asl' => $vch+$gadd->elevation,
            'vch_other' => $ovci,
            'remarks' => $remarks,
            'nip_pelapor' => $nip
        ]);

        return view('vona.show',compact('vona'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show(Vona $vona)
    {
        $vona = Vona::findOrFail($vona->uuid);
        $vona->addView();
        return view('vona.show',compact('vona'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function edit(Vona $vona)
    {
        return Vona::findOrFail($vona->uuid);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vona $vona)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vona $vona)
    {
        $vona = Vona::findOrFail($vona->uuid);
        $issued = 'VONA Gunung Api '.$vona->gunungapi->name.', Issued '.$vona->issued.'UTC';

        if ($vona->delete())
        {
            $data = [
                'success' => 1,
                'message' => $issued.' berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => $issued.' gagal dihapus.'
        ];

        return response()->json($data);

    }

    /**
     * Send VONA to subscribers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, Vona $vona)
    {
        //
    }
}
