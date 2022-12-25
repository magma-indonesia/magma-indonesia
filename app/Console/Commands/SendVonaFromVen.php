<?php

namespace App\Console\Commands;

use App\Services\VonaService;
use App\v1\MagmaVen;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendVonaFromVen extends Command
{
    /**
     * VONA Service
     *
     * @var VonaService
     */
    protected $vonaService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:vona-from-ven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim VONA dari VEN';

    /**
     * Create a new command instance.
     *
     * @param VonaService $vonaService
     */
    public function __construct(VonaService $vonaService)
    {
        $this->vonaService = $vonaService;
        parent::__construct();
    }

    /**
     * Send VONA
     *
     * @param Request $request
     * @param MagmaVen $ven
     * @param VonaService $vonaService
     * @return bool
     */
    protected function send(Request $request, MagmaVen $ven): bool
    {
        $this->vonaService->sendVona(
            $this->vonaService->storeVona($request)->get(),
            $request
        );

        $ven->vona_created_at = now();
        return $ven->save();
    }

    /**
     * Generate new request
     *
     * @param MagmaVen $ven
     * @return Request
     */
    protected function request(MagmaVen $ven): Request
    {
        return new Request([
            'type' => 'real',
            'code' => $ven->gunungapi->ga_code,
            'color' => 'auto',
            'visibility' => $ven->erupt_vis,
            'height' => $ven->erupt_vis ? $ven->erupt_tka : 0,
            'warna_asap' => $ven->erupt_wrn,
            'intensitas' => $ven->erupt_int,
            'arah_abu' => $ven->erupt_arh,
            'date' => "{$ven->erupt_tgl} {$ven->erupt_jam}",
            'terjadi_gempa_letusan' => $ven->erupt_amp ? 1 : 0,
            'terjadi_tremor' => 0,
            'amplitudo' => $ven->erupsi_berlangsung ? 0 : $ven->erupt_amp,
            'durasi' => $ven->erupsi_berlangsung ? 0 : $ven->erupt_drs,
            'amplitudo_tremor' => 0,
            'remarks' => 'Generated from Volcanic Eruption Notice (VEN)',
            'erupsi_berlangsung' => $ven->erupsi_berlangsung,
            'nip_pelapor' => $ven->erupt_usr,
            'old_ven_uuid' => $ven->uuid,
            'group' => config('app.debug') ? 'developer' : 'real',
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vens = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')
            ->whereNull('vona_created_at')->get();

        $this->info('Sending Vona...');

        if ($vens->isNotEmpty()) {
            $vens->each(function ($ven) {
                $this->send(
                    $this->request($ven),
                    $ven
                );
            });
        }

        $this->info('Sent!');
    }
}
