<?php

namespace App\Http\Controllers;

use App\Vona;
use App\v1\Vona as VonaOld;
use App\VonaSubscriber as Subscription;
use App\Gadd;
use App\Http\Requests\VonaCreateRequest;
use App\User;
use App\v1\Gadd as GaddOld;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VonaController extends Controller
{
    protected $latestVona;

    protected $volcano;

    protected $location;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vonas = Vona::select('uuid','issued','cu_code','prev_code','vch_asl','code_id','nip_pelapor')
                ->orderBy('issued','desc')
                // ->where('sent',1)
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

    protected function noticenumber(Request $request)
    {
        $year = now()->format('Y');
        $code = $request->code;

        $vonaCount = VonaOld::where('issued','like', '2022%')->where('ga_code', $code)->count();
        $vonaCount = sprintf('%03d', $vonaCount);
        return $year.$code.$vonaCount;
    }

    protected function datetimeUtc($datetime, $tz)
    {
        $datetime_utc = Carbon::createFromTimeString($datetime, $tz)->setTimezone('UTC');

        return $datetime_utc;
    }

    protected function issued(Request $request)
    {
        switch ($this->volcano->ga_zonearea) {
            case 'WIB':
                $tz = 'Asia/Jakarta';
                break;
            case 'WITA':
                $tz = 'Asia/Makassar';
                break;
            default:
                $tz = 'Asia/Jayapura';
                break;
        }

        return $this->datetimeUtc($request->date, $tz)->format('Y-m-d H:i:s');
    }

    protected function previous_code(Request $request)
    {
        if (is_null($this->latestVona))
            return 'unassigned';

        return $this->latestVona->pre_avcode;
    }

    protected function get_color(Request $request)
    {
        if ($request->height >= 6000)
            return 'RED';

        if ($request->height > 0)
            return 'ORANGE';

        return 'YELLOW';
    }

    protected function current_code(Request $request)
    {
        return $request->visibility ? $this->get_color($request) : 'ORANGE';
    }

    protected function convertLatitude()
    {
        $coordinate = $this->volcano->ga_lat_gapi;
        [$degree, $decimal] = explode('.', $coordinate);
        $symbol = $degree > 0 ? 'N' : 'S';
        $decimal = abs($coordinate) - abs($degree);
        $minute = floor($decimal * 60);
        $second = round(($decimal*3600) - ($minute * 60));

        $degree = sprintf('%02s', abs($degree));
        $minute = sprintf('%02s', abs($minute));
        $second = sprintf('%02s', abs($second));

        return "$symbol $degree deg $minute min $second sec";
    }

    protected function convertLongitude()
    {
        $coordinate = $this->volcano->ga_lon_gapi;
        [$degree, $decimal] = explode('.', $coordinate);

        $decimal = abs($coordinate) - abs($degree);
        $minute = floor($decimal * 60);
        $second = round(($decimal * 3600) - ($minute * 60));

        $degree = sprintf('%02s', abs($degree));
        $minute = sprintf('%02s', abs($minute));
        $second = sprintf('%02s', abs($second));

        return "E $degree deg $minute min $second sec";
    }

    protected function location()
    {
        return "{$this->convertLatitude()} {$this->convertLongitude()}";
    }

    protected function volcano(Request $request)
    {
        $this->volcano = GaddOld::select('ga_code','ga_nama_gapi','ga_id_smithsonian','ga_elev_gapi','ga_lon_gapi','ga_lat_gapi','ga_prov_gapi', 'ga_prov_gapi_en','ga_zonearea')->where('ga_code', $request->code)->first();
    }

    protected function latestVona(Request $request)
    {
        $this->latestVona = VonaOld::with('volcano:ga_code,ga_nama_gapi,ga_id_smithsonian,ga_elev_gapi,ga_lon_gapi,ga_lat_gapi,ga_prov_gapi,ga_zonearea')
            ->where('ga_code', $request->code)
            ->where('sent', 1)
            ->orderBy('issued_time', 'desc')
            ->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\VonaCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VonaCreateRequest $request)
    {
        $this->latestVona($request);
        $this->volcano($request);

        $vona = Vona::create([
            'noticenumber' => $this->noticenumber($request),
            'issued' => $this->issued($request),
            'type' => Str::upper($request->type),
            'code_id' => $request->code,
            'is_visible' => $request->visibility,
            'is_continuing' => $request->erupsi_berlangsung,
            'current_code' => $this->current_code($request),
            'previous_code' => $this->previous_code($request),
            'ash_height' => $request->height,
            'ash_color' => $request->warna_asap,
            'ash_intensity' => $request->intensitas,
            'ash_directions' => $request->arah_abu,
            'amplitude' => $request->amplitudo,
            'duration' => $request->durasi ?? 0,
            'nip_pelapor' => auth()->user()->nip,
        ]);

        $vona->load('user','gunungapi');

        return view('vona.show',[
            'vona' => $vona,
            'location' => $this->location()
        ]);

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
