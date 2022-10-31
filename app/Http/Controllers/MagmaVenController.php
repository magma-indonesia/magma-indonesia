<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LaporanLetusanRequest;
use App\MagmaVen;
use App\v1\MagmaVen as OldVen;
use App\Gadd;
use App\Seismometer;
use App\Vona;
use Carbon\Carbon;
use Shivella\Bitly\BitlyServiceProvider;

use App\Traits\VisualLetusan;
use App\VarRekomendasi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;
use Shivella\Bitly\Client\BitlyClient;
use Shivella\Bitly\Facade\Bitly;

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
        $vens = MagmaVen::with('gunungapi:code,name')
            ->orderBy('datetime_utc','desc')->paginate(30);

        return view('gunungapi.letusan.index', [
            'vens' => $vens
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gunungapi.letusan.create', [
            'gadds' => Gadd::has('seismometers')
                        ->with('seismometers')
                        ->select('code', 'name')
                        ->orderBy('name')
                        ->get(),
            'rekomendasis' => VarRekomendasi::select('id','code_id','rekomendasi')
                                ->where('code_id','AGU')
                                ->where('status',1)
                                ->orderByDesc('created_at')
                                ->get(),
        ]);
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
        $issued = Carbon::createFromFormat('Y-m-d H:i:s', $ven->date, 'Asia/Jakarta')
            ->setTimezone('UTC')
            ->toDateTimeString();

        $location = $this->location('lat',$ven->gunungapi->latitude).$this->location('lon',$ven->gunungapi->longitude);

        $issued_utc = Carbon::createFromFormat('Y-m-d H:i:s',$issued)->format('Hi');
        $issued_lt = Carbon::createFromFormat('Y-m-d H:i:s', $ven->date, 'Asia/Jakarta')->format('Hi');

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

        return $vona->save() ? true : false;

    }

    protected function generateLetusan($request)
    {
        return $request->jenis == 'apg' ? 'Awan Panas Guguran' : 'Letusan';
    }

    protected function sendSms($message, $locations)
    {
        $url = config('app.sms_url').'/pvmbg/alert/pvmbg';

        $messages = [
            'user' => config('app.sms_user'),
            'pwd' => config('app.sms_password'),
            'sms' => $message,
            'lokasi' => implode(',', $locations),
        ];

        $client = new Client();
        $request = $client->get($url,[
                'query' => $messages
            ]
        );

        $response = json_decode($request->getBody(), true);

        return $response;
    }

    protected function generateSms($request)
    {
        $gadd = Gadd::select('code','name', 'zonearea')
            ->with('sms_locations')
            ->where('code', $request->code)
            ->first();

        switch ($request->status) {
            case '1':
                $status = 'Level I (Normal)';
                break;
            case '2':
                $status = 'Level II (Waspada)';
                break;
            case '3':
                $status = 'Level III (Siaga)';
                break;
            default:
                $status = 'Level IV (Awas)';
                break;
        }

        $masking = config('app.sms_masking');
        $message = "Terjadi {$this->generateLetusan($request)} G. {$gadd->name} - {$status} pada {$request->date}{$gadd->zonearea} {$masking}";
        $locations = $gadd->sms_locations->pluck('kode_kabupaten');

        // $response = $this->sendSms($message, $locations);

        return [
            'message' => $message,
            'message_length' => strlen($message),
            'message_encoded' => urlencode($message),
            'location' => $locations,
            // 'responses' => $response,
        ];
    }

    protected function datetimeUtc($datetime, $tz)
    {
        $datetime_utc = Carbon::createFromTimeString($datetime, $tz)->setTimezone('UTC');

        return $datetime_utc;
    }

    protected function saveOldVen($request)
    {
        $datetime = Carbon::createFromTimeString($request->date);
        $date = $datetime->format('Y-m-d');
        $time = $datetime->format('H:i');

        switch ($request->status) {
            case '1':
                $status = 'Level I (Normal)';
                break;
            case '2':
                $status = 'Level II (Waspada)';
                break;
            case '3':
                $status = 'Level III (Siaga)';
                break;
            default:
                $status = 'Level IV (Awas)';
                break;
        }

        $oldVen = OldVen::firstOrCreate([
            'ga_code' => $request->code,
            'erupt_tgl' => $date,
            'erupt_jam' => $time,
        ],[
            'erupsi_berlangsung' => $request->erupsi_berlangsung,
            'erupt_vis' => $request->visibility,
            'erupt_tka' => $request->visibility ? $request->height : 0,
            'erupt_wrn' => $request->visibility ? implode(', ', $request->warna_asap) : '-',
            'erupt_int' => $request->visibility ? implode(', ', $request->intensitas) : '-',
            'erupt_arh' => $request->visibility ? implode(', ', $request->arah_abu) : '-',
            'erupt_amp' => $request->visibility ? $request->amplitudo : 0,
            'erupt_drs' => $request->visibility ? $request->durasi : 0,
            'erupt_pht' => $request->visibility ? '-' : '-',
            'erupt_sta' => $status,
            'erupt_rek' => VarRekomendasi::findOrFail($request->rekomendasi)->rekomendasi,
            'erupt_ket' => $request->lainnya ?: '-',
            'erupt_usr' => auth()->user()->nip,
            'is_published' => $request->is_blasted,
            'is_blasted' => $request->is_blasted,
            'erupt_tsp' => now(),
        ]);

        return $oldVen;
    }

    protected function saveVen($request)
    {
        $gadd = Gadd::where('code', $request->code)->firstOrFail();

        switch ($gadd->zonearea) {
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

        $ven = MagmaVen::firstOrCreate([
            'code_id' => $request->code,
            'jenis' => $request->jenis,
            'datetime_utc' => $this->datetimeUtc($request->date, $tz)->format('Y-m-d H:i:s'),
        ],[
            'timezone' => $tz,
            'erupsi_berlangsung' => $request->erupsi_berlangsung,
            'visibility' => $request->visibility,
            'height' => $request->height ?? 0,
            'warna_abu' => $request->warna_asap ?? null,
            'intensitas' => $request->intensitas ?? null,
            'arah_abu' => $request->arah_abu ?? null,
            'amplitudo' => $request->amplitudo,
            'durasi' => $request->durasi,
            'seismometer_id' => $request->seismometer_id,
            'status' => $request->status,
            'distance' => $request->distance,
            'arah_guguran' => $request->arah_guguran,
            'foto_letusan' => null,
            'thumbnail' => null,
            'informasi_lainnya' => $request->lainnya ?? null,
            'rekomendasi_id' => $request->rekomendasi,
            'has_vona' => $request->draft,
            'is_blasted' => $request->is_blasted,
            'nip_pelapor' => auth()->user()->nip,
            'published_at' => now(),
        ]);

        return $ven;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\LaporanLetusanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LaporanLetusanRequest $request)
    {
        $oldVen = $this->saveOldVen($request);
        $urlOldVen = URL::signedRoute('v1.gunungapi.ven.show', $oldVen);

        $ven = $this->saveVen($request);

        return [
            'request' => $request->toArray(),
            'sms' => $request->is_blasted ? $this->generateSms($request) : null,
            'url_old_ven' => $urlOldVen,
            'vona' => null,
        ];

        // if ($saveVen){
        //     return redirect()->route('chambers.letusan.show',['uuid'=> $ven->uuid]);
        // }

        // return redirect()->route('chambers.letusan.index')
        //     ->with('flash_message','Informasi letusan gagal ditambahkan. ');
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
        $visual = $this->visualLetusan($ven);
        $ven->addView();
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

        $message = 'Laporan Letusan Gunung '.$ven->gunungapi->name.', tanggal '.$ven->date.' pukul '.$ven->date->format('H:i').' '.$ven->gunungapi->zonearea;

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
