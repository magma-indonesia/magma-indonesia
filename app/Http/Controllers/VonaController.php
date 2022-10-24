<?php

namespace App\Http\Controllers;

use App\Vona;
use App\Gadd;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\VonaCreateRequest;
use App\Mail\VonaSend;
use App\Traits\VonaTrait;
use App\User;
use App\v1\Vona as V1Vona;
use App\VonaSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class VonaController extends Controller
{

    use VonaTrait;

    public function __construct()
    {
        Carbon::setLocale('en');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vonas = Vona::select('uuid','issued', 'current_code', 'previous_code', 'ash_height','code_id','nip_pelapor')
                ->orderBy('issued','desc')
                ->paginate(30,['*'],'vona_page');

        return view('vona.index',compact('vonas'));
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
     * @param  \App\Http\Requests\VonaCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VonaCreateRequest $request)
    {
        $vona = Vona::create([
            'issued' => $this->issued($request),
            'type' => Str::upper($request->type),
            'code_id' => $request->code,
            'is_visible' => $request->visibility,
            'is_continuing' => $request->erupsi_berlangsung,
            'current_code' => $this->currentCode($request),
            'previous_code' => $this->previousCode($request),
            'ash_height' => $request->height,
            'ash_color' => $request->warna_asap,
            'ash_intensity' => $request->intensitas,
            'ash_directions' => $request->arah_abu,
            'amplitude' => $request->amplitudo,
            'duration' => $request->durasi ?? 0,
            'remarks' => $request->remarks,
            'nip_pelapor' => auth()->user()->nip,
        ]);

        $oldVona = $this->storeToOldVona($request, $vona);

        $vona->update([
            'old_id' => $oldVona->no,
            'noticenumber' => $oldVona->notic_number,
        ]);

        return redirect()->route('chambers.vona.show', ['uuid' => $vona->uuid]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show(Vona $vona)
    {
        $vona = Vona::findOrFail($vona->uuid)->load('gunungapi');

        return view('vona.show', [
            'vona' => $vona,
            'location' => $this->location($vona),
            'volcano_activity_summary' => $this->volcanoActivitySummary($vona),
            'volcanic_cloud_height' => $this->volcanicCloudHeight($vona),
            'other_volcanic_cloud_information' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => $this->remarks($vona),
        ]);
    }

    protected function subscribers(Request $request)
    {
        return VonaSubscriber::where($request->group, 1)
                    ->where('status', 1)
                    ->get();
    }

    public function sendEmail(Vona $vona, SendEmailRequest $request)
    {
        $subs = $this->subscribers($request);
        $vona->load('gunungapi');

        $subs->each(function ($sub) use ($vona) {
            Mail::to($sub->email)
                ->queue(new VonaSend($vona));
        });

        $vona->update([
            'is_sent' => 1,
        ]);

        return redirect()->route('chambers.vona.index');
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
        $oldId = $vona->old_id;

        if ($vona->delete())
        {
            $data = [
                'success' => 1,
                'message' => $issued.' berhasil dihapus.'
            ];

            if (!is_null($oldId)) {
                if ($oldVona = V1Vona::where('no', $oldId)->first()) {
                    $oldVona->delete();
                }
            }

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => $issued.' gagal dihapus.'
        ];

        return response()->json($data);

    }
}