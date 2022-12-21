<?php

namespace App\Services;

use App\Mail\VonaSend;
use App\Notifications\VonaTelegram;
use App\Traits\VonaTrait;
use App\v1\MagmaVen;
use App\v1\Vona as V1Vona;
use App\Vona;
use App\VonaSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class VonaService
{
    use VonaTrait;

    /**
     * Store VONA
     *
     * @param Request $request
     * @return Vona
     */
    public function storeVona(Request $request): Vona
    {
        $vona = Vona::firstOrCreate([
            'issued' => $this->issued($request),
            'type' => Str::upper($request->type),
            'code_id' => $request->code,
        ], [
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
            'nip_pelapor' => $request->has('nip_pelapor') ? $request->nip_pelapor :auth()->user()->nip,
            'old_ven_uuid' => $request->old_ven_uuid,
        ]);

        $oldVona = $this->storeToOldVona($request, $vona);

        $vona->update([
            'old_id' => $oldVona->no,
            'noticenumber' => $oldVona->notice_number,
        ]);

        return $vona;
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

        if ($request->group === 'real') {
            $this->sendToTelegram($vona);
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
    }

    /**
     * Sending VONA notification
     *
     * @param Vona $vona
     * @param Request $request
     * @return void
     */
    public function sendVona(Vona $vona, Request $request): void
    {
        if (in_array($request->group, ['developer', 'exercise', 'real', 'pvmbg'])) {
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

        $this->clearVonaCache();
    }

    /**
     * Undocumented function
     *
     * @param Vona $vona
     * @return PDF
     */
    public function downloadPdf(Vona $vona): PDF
    {
        $vona->load('gunungapi');

        $pdf = PDF::loadView('vona.pdf', [
            'vona' => $vona,
            'location' => $this->location($vona),
            'volcano_activity_summary' => $this->volcanoActivitySummary($vona),
            'volcanic_cloud_height' => $this->volcanicCloudHeight($vona),
            'other_volcanic_cloud_information' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => $this->remarks($vona),
            'ven' => $vona->old_ven_uuid ?
                        MagmaVen::where('uuid', $vona->old_ven_uuid)->first() :
                        null
        ]);

        $filename = "{$vona->gunungapi->name} {$vona->issued}";

        return $pdf->download(Str::slug($filename));
    }
}