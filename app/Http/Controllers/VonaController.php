<?php

namespace App\Http\Controllers;

use App\Vona;
use App\Gadd;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\VonaCreateRequest;
use App\Services\VonaService;
use App\Traits\VonaTrait;
use App\v1\MagmaVen;
use App\v1\Vona as V1Vona;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        return view('vona.create', [
            'gadds' => Gadd::select('name','code')->orderBy('name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\VonaCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VonaCreateRequest $request, VonaService $vonaService)
    {
        $vona = $vonaService->storeVona($request)->get();

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
        $vona = $vona->load('gunungapi');
        $ven = $vona->old_ven_uuid ?
            MagmaVen::where('uuid', $vona->old_ven_uuid)->first() :
            null;

        return view('vona.show', [
            'vona' => $vona,
            'location' => $this->location($vona),
            'volcano_activity_summary' => $this->volcanoActivitySummary($vona),
            'volcanic_cloud_height' => $this->volcanicCloudHeight($vona),
            'other_volcanic_cloud_information' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => $this->remarks($vona),
            'ven' => $ven,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Vona $vona
     * @param SendEmailRequest $request
     * @param VonaService $vonaService
     * @return \Illuminate\Http\Response
     */
    public function send(Vona $vona, SendEmailRequest $request, VonaService $vonaService)
    {
        $vonaService->sendVona($vona, $request);

        return redirect()->route('chambers.vona.index')
            ->with('message', 'VONA berhasil dikirim dan dipublikasikan');
    }

    /**
     * Generate PDF for VONA
     *
     * @param Request $request
     * @param VonaService $vonaService
     * @return \Illuminate\Http\Response
     */
    public function pdf(Vona $vona, VonaService $vonaService)
    {
        return $vonaService->downloadPdf($vona);
    }

    /**
     * Updating Old VONA Value
     *
     * @param Vona $vona
     * @return void
     */
    public function reupdate(Vona $vona)
    {
        $this->updateToOldVona($vona);
        $this->clearVonaCache();
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
        return view('vona.edit', [
            'gadds' => Gadd::select('name', 'code')->orderBy('name')->get(),
            'vona' => $vona->load('gunungapi')
        ]);

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

            $this->clearVonaCache();
            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => $issued.' gagal dihapus.'
        ];

        return response()->json($data);

    }
}