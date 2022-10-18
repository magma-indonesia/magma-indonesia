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
        $vonas = Vona::select('uuid','issued', 'current_code', 'previous_code', 'ash_height','code_id','nip_pelapor')
                ->orderBy('issued','desc')
                // ->where('is_sent',1)
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
     * Get Noticenumber of VONA
     *
     * @param Request $request
     * @return String
     */
    protected function noticenumber(Request $request): string
    {
        $year = now()->format('Y');
        $code = $request->code;

        $vonaCount = VonaOld::where('issued','like', '2022%')->where('ga_code', $code)->count();
        $vonaCount = sprintf('%03d', $vonaCount);
        return $year.$code.$vonaCount;
    }

    /**
     * Get date time in UTC
     *
     * @param string $datetime
     * @param string $tz
     * @return Carbon
     */
    protected function datetimeUtc(string $datetime, string $tz): Carbon
    {
        $datetime_utc = Carbon::createFromTimeString($datetime, $tz)->setTimezone('UTC');

        return $datetime_utc;
    }

    /**
     * Issued date (UTC) in VONA format
     *
     * @param Request $request
     * @return string
     */
    protected function issued(Request $request): string
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

    /**
     * Get preivous code
     *
     * @return string
     */
    protected function previous_code(): string
    {
        if (is_null($this->latestVona))
            return 'unassigned';

        return $this->latestVona->pre_avcode;
    }

    /**
     * Get vona color code
     *
     * @param Request $request
     * @return string
     */
    protected function get_color(Request $request): string
    {
        if ($request->height >= 6000)
            return 'RED';

        if ($request->height > 0)
            return 'ORANGE';

        return 'YELLOW';
    }

    /**
     * Get vona current coior code
     *
     * @param Request $request
     * @return string
     */
    protected function current_code(Request $request): string
    {
        return $request->visibility ? $this->get_color($request) : 'ORANGE';
    }

    protected function coordinateToString($coordinate, string $type)
    {
        [$degree, $decimal] = explode('.', $coordinate);

        $symbol = $degree > 0 ? 'N' : 'S';
        if ($type === 'longitude') {
            $symbol = 'E';
        }

        $decimal = abs($coordinate) - abs($degree);
        $minute = floor($decimal * 60);
        $second = round(($decimal * 3600) - ($minute * 60));

        $degree = $degree == '0' ? '0' : sprintf('%02s', abs($degree));
        $minute = sprintf('%02s', abs($minute));
        $second = sprintf('%02s', abs($second));

        return "$symbol $degree deg $minute min $second sec";
    }

    protected function convertLatitude(): string
    {
        $coordinate = $this->volcano->ga_lat_gapi;
        return $this->coordinateToString($coordinate, 'latitude');
    }

    protected function convertLongitude(): string
    {
        $coordinate = $this->volcano->ga_lon_gapi;
        return $this->coordinateToString($coordinate, 'longitude');
    }

    protected function location(): string
    {
        return "{$this->convertLatitude()} {$this->convertLongitude()}";
    }

    protected function volcano(string $code): void
    {
        $this->volcano = GaddOld::select('ga_code','ga_nama_gapi','ga_id_smithsonian','ga_elev_gapi','ga_lon_gapi','ga_lat_gapi','ga_prov_gapi', 'ga_prov_gapi_en','ga_zonearea')->where('ga_code', $code)->first();
    }

    protected function latestVona(Request $request): void
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
        $this->volcano($request->code);

        $vona = Vona::create([
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

    public function volcanoActivitySummary(Vona $vona)
    {
        $deskripsi = "Best estimate of ash-cloud top is around 13363 FT (4176 M) above sea level, may be higher than what can be observed clearly. Source of height data: ground observer."
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
        $this->volcano($vona->code_id);
        return view('vona.show', [
            'vona' => $vona,
            'location' => $this->location(),
            'volcano_activity_summary' => $this->volcanoActivitySummary($vona),
        ]);
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
