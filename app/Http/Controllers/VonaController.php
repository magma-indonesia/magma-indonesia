<?php

namespace App\Http\Controllers;

use App\Vona;
use App\Gadd;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\VonaCreateRequest;
use App\Mail\VonaSend;
use App\Notifications\VonaTelegram;
use App\Traits\VonaTrait;
use App\User;
use App\v1\Vona as V1Vona;
use App\VonaSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Cache;

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
        $vonas = Vona::select('uuid','type','issued','noticenumber','current_code', 'previous_code', 'ash_height','code_id','nip_pelapor', 'is_sent')
                ->orderBy('issued','desc')
                ->paginate(100,['*'],'vona_page');

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
            'ash_height' => $request->visibility ? $request->height : 0,
            'ash_color' => $request->visibility ? $request->warna_asap : null,
            'ash_intensity' => $request->visibility ? $request->intensitas : null,
            'ash_directions' => $request->visibility ? $request->arah_abu : null,
            'amplitude' => ($request->terjadi_gempa_letusan || $request->code == 'green') ?
                ($request->amplitudo ?? 0) : 0,
            'amplitude_tremor' => ($request->terjadi_tremor || $request->code == 'green') ?
                ($request->amplitudo_tremor ?? 0) : 0,
            'duration' => $this->duration($request),
            'remarks' => $request->remarks,
            'nip_pelapor' => auth()->user()->nip,
        ]);

        $oldVona = $this->storeToOldVona($request, $vona);

        $vona->update([
            'old_id' => $oldVona->no,
            'noticenumber' => $oldVona->notice_number,
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

    /**
     * Clear cache VONA
     *
     * @return void
     */
    protected function clearVonaCache(): void
    {
        Cache::tags(['fp-vona.index', 'api-vona.index'])->flush();
    }

    /**
     * Get all subscribers
     *
     * @param Request $request
     * @return Collection
     */
    protected function subscribers(Request $request): Collection
    {
        return VonaSubscriber::where($request->group, 1)
                ->where('status', 1)
                ->get();
    }

    /**
     * Update is_sent column
     *
     * @param Vona $vona
     * @return void
     */
    protected function updateIsSent(Vona $vona): void
    {
        $vona->update([
            'is_sent' => 1,
        ]);

        $oldVona = V1Vona::where('no', $vona->old_id)->first();
        $oldVona->update([
            'sent' => 1,
        ]);

        $this->clearVonaCache();
    }

    /**
     * Send VONA to telegram channel
     *
     * @param Vona $vona
     * @return void
     */
    protected function sendToTelegram(Vona $vona): void
    {
        if (request()->user()->hasRole('Super Admin')) {
            $vona->notify(new VonaTelegram($vona));
        }

        if (is_null($vona->sent_to_telegram)) {
            $vona->notify(new VonaTelegram($vona));
            $vona->update([
                'sent_to_telegram' => now(),
            ]);
        }
    }

    /**
     * Send or unsend VONA
     *
     * @param Vona $vona
     * @param Request $request
     * @return void
     */
    protected function sendOrUnsend(Vona $vona, Request $request): void
    {
        $vona->update([
            'is_sent' => $request->group === 'send' ? 1 : 0,
        ]);

        $oldVona = V1Vona::where('no', $vona->old_id)->first();
        $oldVona->update([
            'sent' => $request->group === 'send' ? 1 : 0,
        ]);

        $this->clearVonaCache();
    }

    /**
     * Send email to stakeholder
     *
     * @param Vona $vona
     * @param Request $request
     * @return void
     */
    protected function sendEmail(Vona $vona, Request $request): void
    {
        $subs = $this->subscribers($request);
        $vona->load('gunungapi');

        $subs->each(function ($sub) use ($vona) {
            Mail::to($sub->email)
                ->queue(new VonaSend($vona));
        });

        if ($request->group !== 'pvmbg') {
            $this->sendToTelegram($vona);
        }
    }

    /**
     * Send VONA to stakeholders
     *
     * @param  \App\Vona  $vona
     * @param SendEmailRequest $request
     * @return \Illuminate\Http\Response
     */
    public function send(Vona $vona, SendEmailRequest $request)
    {
        if (in_array($request->group, ['exercise', 'real', 'pvmbg'])) {
            $this->sendEmail($vona, $request);
            $this->updateIsSent($vona);
        }

        if (in_array($request->group, ['send', 'unsend'])) {
            $this->sendOrUnsend($vona, $request);
        }

        if ($request->group === 'telegram') {
            $this->sendToTelegram($vona, $request);
            $this->updateIsSent($vona);
        }

        return redirect()->route('chambers.vona.index');
    }

    /**
     * Generate PDF for VONA
     *
     * @param Vona $vona
     * @param Request $request
     * @return void
     */
    public function pdf(Vona $vona, Request $request)
    {
        $vona->load('gunungapi');

        $pdf = PDF::loadView('vona.pdf', [
            'vona' => $vona,
            'location' => $this->location($vona),
            'volcano_activity_summary' => $this->volcanoActivitySummary($vona),
            'volcanic_cloud_height' => $this->volcanicCloudHeight($vona),
            'other_volcanic_cloud_information' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => $this->remarks($vona),
        ]);

        $filename = "{$vona->gunungapi->name} {$vona->issued}";

        return $pdf->download(Str::slug($filename));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function edit(Vona $vona)
    {
        return Vona::findOrFail($vona->uuid)->load('gunungapi');
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