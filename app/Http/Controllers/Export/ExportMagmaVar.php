<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MagmaVar as NewVar;
use App\v1\MagmaVar as OldVar;
use App\Traits\ExportHelper;

class ExportMagmaVar extends Export
{
    use ExportHelper;
    
    protected $noticenumber;
    protected $pj;
    protected $verifikator;
    protected $lainnya;
    protected $status;
    protected $tasap_min;
    protected $tasap_max;
    protected $wasap;
    protected $intasap;
    protected $tekasap;

    public function __construct()
    {
        ini_set('max_execution_time',1200);
    }

    public function export()
    {

        $this->new = NewVar::with(
            'gunungapi','pj','verifikator','visual',
            'klimatologi','gempa','rekomendasi','keterangan')
        ->orderBy('created_at')
        ->whereBetween('var_data_date',['2018-05-25',now()->format('Y-m-d')]);

        $this->new->chunk(5000, function($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->setNoticeNumber()
                    ->setStatus()
                    ->setTasapMin()
                    ->setTasapMax()
                    ->setWasap()
                    ->setIntasap()
                    ->setTekasap()
                    ->updateOldVar();
            }
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars berhasil diperbarui', 'count' => OldVar::count() ] 
                    : [ 'success' => 0, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars gagal diperbarui', 'count' => 0 ];

        return response()->json($data);
        
    }

    protected function updateOldVar()
    {

        try {

            $update = OldVar::firstOrCreate(
                [
                    'ga_code' => $this->item->code_id,
                    'var_noticenumber' => $this->noticenumber
                ],
                [
                    'var_image' => 'https://magma.vsi.esdm.go.id/img/empty.jpg',
                    'var_image_create' => 'Taken 2018:07:27 15:26:21 WIB (UTC +7)',
                    'var_issued' => now()->format('d/m/Y H:i:s'),
                    'ga_nama_gapi' => $this->item->gunungapi->name,
                    'ga_id_smithsonian' => $this->item->gunungapi->smithsonian_id,
                    'cu_status' => $this->status,
                    'pre_status' => $this->status,
                    'var_source' => 'Pos Pengamatan Gunung Api '.$this->item->gunungapi->name,
                    'volcano_location' => $this->item->gunungapi->latitude.', '.$this->item->gunungapi->longitude,
                    'area' => $this->item->gunungapi->district.', '.$this->item->gunungapi->province,
                    'summit_elevation' => $this->item->gunungapi->elevation,
                    'var_perwkt' => $this->item->var_perwkt.' Jam',
                    'periode' => $this->item->periode,
                    'var_visibility' => implode(', ',$this->item->visual->visibility),
                    'var_cuaca' => implode(', ',$this->item->klimatologi->cuaca),
                    'var_curah_hujan' => $this->item->klimatologi->curah_hujan,
                    'var_suhumin' => $this->item->klimatologi->suhumin,
                    'var_suhumax' => $this->item->klimatologi->suhumax,
                    'var_kelembabanmin' => $this->item->klimatologi->lembabmin,
                    'var_kelembabanmax' => $this->item->klimatologi->lembabmax,
                    'var_tekananmin' => $this->item->klimatologi->tekmin,
                    'var_tekananmax' => $this->item->klimatologi->tekmax,
                    'var_kecangin' => implode(', ',$this->item->klimatologi->kecangin),
                    'var_arangin' => implode(', ',$this->item->klimatologi->arahangin),
                    'var_asap' => $this->item->visual->visual_asap,
                    'var_tasap_min' => $this->tasap_min,
                    'var_tasap' => $this->tasap_max,
                    'var_wasap' => $this->wasap,
                    'var_intasap' => $this->intasap,
                    'var_tekasap' => $this->tekasap,
                    'var_viskawah' => $this->item->visual->visual_kawah,
                    'var_tej' => optional(optional($this->item->gempa)->tej)->jumlah ? $this->item->gempa->tej->jumlah : 0,
                    'var_tej_amin' => optional(optional($this->item->gempa)->tej)->amin ? $this->item->gempa->tej->amin : 0,
                    'var_tej_amax' => optional(optional($this->item->gempa)->tej)->amax ? $this->item->gempa->tej->amax : 0,
                    'var_tej_spmin' => optional(optional($this->item->gempa)->tej)->spmin ? $this->item->gempa->tej->spmin : 0,
                    'var_tej_spmax' => optional(optional($this->item->gempa)->tej)->spmax ? $this->item->gempa->tej->spmax : 0,
                    'var_tej_dmin' => optional(optional($this->item->gempa)->tej)->dmin ? $this->item->gempa->tej->dmin : 0,
                    'var_tej_dmax' => optional(optional($this->item->gempa)->tej)->dmax ? $this->item->gempa->tej->dmax : 0,
                    'var_tel' => optional(optional($this->item->gempa)->tel)->jumlah ? $this->item->gempa->tel->jumlah : 0,
                    'var_tel_amin' => optional(optional($this->item->gempa)->tel)->amin ? $this->item->gempa->tel->amin : 0,
                    'var_tel_amax' => optional(optional($this->item->gempa)->tel)->amax ? $this->item->gempa->tel->amax : 0,
                    'var_tel_spmin' => optional(optional($this->item->gempa)->tel)->spmin ? $this->item->gempa->tel->spmin : 0,
                    'var_tel_spmax' => optional(optional($this->item->gempa)->tel)->spmax ? $this->item->gempa->tel->spmax : 0,
                    'var_tel_dmin' => optional(optional($this->item->gempa)->tel)->dmin ? $this->item->gempa->tel->dmin : 0,
                    'var_tel_dmax' => optional(optional($this->item->gempa)->tel)->dmax ? $this->item->gempa->tel->dmax : 0,
                    'var_vlp' => optional(optional($this->item->gempa)->vlp)->jumlah ? $this->item->gempa->vlp->jumlah : 0,
                    'var_vlp_amin' => optional(optional($this->item->gempa)->vlp)->amin ? $this->item->gempa->vlp->amin : 0,
                    'var_vlp_amax' => optional(optional($this->item->gempa)->vlp)->amax ? $this->item->gempa->vlp->amax : 0,
                    'var_vlp_dmin' => optional(optional($this->item->gempa)->vlp)->dmin ? $this->item->gempa->vlp->dmin : 0,
                    'var_vlp_dmax' => optional(optional($this->item->gempa)->vlp)->dmax ? $this->item->gempa->vlp->dmax : 0,
                    'var_dpt' => optional(optional($this->item->gempa)->dpt)->jumlah ? $this->item->gempa->dpt->jumlah : 0,
                    'var_dpt_amin' => optional(optional($this->item->gempa)->dpt)->amin ? $this->item->gempa->dpt->amin : 0,
                    'var_dpt_amax' => optional(optional($this->item->gempa)->dpt)->amax ? $this->item->gempa->dpt->amax : 0,
                    'var_dpt_dmin' => optional(optional($this->item->gempa)->dpt)->dmin ? $this->item->gempa->dpt->dmin : 0,
                    'var_dpt_dmax' => optional(optional($this->item->gempa)->dpt)->dmax ? $this->item->gempa->dpt->dmax : 0,
                    'var_dev' => optional(optional($this->item->gempa)->dev)->jumlah ? $this->item->gempa->dev->jumlah : 0,
                    'var_dev_amin' => optional(optional($this->item->gempa)->dev)->amin ? $this->item->gempa->dev->amin : 0,
                    'var_dev_amax' => optional(optional($this->item->gempa)->dev)->amax ? $this->item->gempa->dev->amax : 0,
                    'var_dev_spmin' => optional(optional($this->item->gempa)->dev)->spmin ? $this->item->gempa->dev->spmin : 0,
                    'var_dev_spmax' => optional(optional($this->item->gempa)->dev)->spmax ? $this->item->gempa->dev->spmax : 0,
                    'var_dev_dmin' => optional(optional($this->item->gempa)->dev)->dmin ? $this->item->gempa->dev->dmin : 0,
                    'var_dev_dmax' => optional(optional($this->item->gempa)->dev)->dmax ? $this->item->gempa->dev->dmax : 0,
                    'var_vta' => optional(optional($this->item->gempa)->vta)->jumlah ? $this->item->gempa->vta->jumlah : 0,
                    'var_vta_amin' => optional(optional($this->item->gempa)->vta)->amin ? $this->item->gempa->vta->amin : 0,
                    'var_vta_amax' => optional(optional($this->item->gempa)->vta)->amax ? $this->item->gempa->vta->amax : 0,
                    'var_vta_spmin' => optional(optional($this->item->gempa)->vta)->spmin ? $this->item->gempa->vta->spmin : 0,
                    'var_vta_spmax' => optional(optional($this->item->gempa)->vta)->spmax ? $this->item->gempa->vta->spmax : 0,
                    'var_vta_dmin' => optional(optional($this->item->gempa)->vta)->dmin ? $this->item->gempa->vta->dmin : 0,
                    'var_vta_dmax' => optional(optional($this->item->gempa)->vta)->dmax ? $this->item->gempa->vta->dmax : 0,
                    'var_vtb' => optional(optional($this->item->gempa)->vtb)->jumlah ? $this->item->gempa->vtb->jumlah : 0,
                    'var_vtb_amin' => optional(optional($this->item->gempa)->vtb)->amin ? $this->item->gempa->vtb->amin : 0,
                    'var_vtb_amax' => optional(optional($this->item->gempa)->vtb)->amax ? $this->item->gempa->vtb->amax : 0,
                    'var_vtb_dmin' => optional(optional($this->item->gempa)->vtb)->dmin ? $this->item->gempa->vtb->dmin : 0,
                    'var_vtb_dmax' => optional(optional($this->item->gempa)->vtb)->dmax ? $this->item->gempa->vtb->dmax : 0,
                    'var_hyb' => optional(optional($this->item->gempa)->hyb)->jumlah ? $this->item->gempa->hyb->jumlah : 0,
                    'var_hyb_amin' => optional(optional($this->item->gempa)->hyb)->amin ? $this->item->gempa->hyb->amin : 0,
                    'var_hyb_amax' => optional(optional($this->item->gempa)->hyb)->amax ? $this->item->gempa->hyb->amax : 0,
                    'var_hyb_spmin' => optional(optional($this->item->gempa)->hyb)->spmin ? $this->item->gempa->hyb->spmin : 0,
                    'var_hyb_spmax' => optional(optional($this->item->gempa)->hyb)->spmax ? $this->item->gempa->hyb->spmax : 0,
                    'var_hyb_dmin' => optional(optional($this->item->gempa)->hyb)->dmin ? $this->item->gempa->hyb->dmin : 0,
                    'var_hyb_dmax' => optional(optional($this->item->gempa)->hyb)->dmax ? $this->item->gempa->hyb->dmax : 0,
                    'var_lof' => optional(optional($this->item->gempa)->lof)->jumlah ? $this->item->gempa->lof->jumlah : 0,
                    'var_lof_amin' => optional(optional($this->item->gempa)->lof)->amin ? $this->item->gempa->lof->amin : 0,
                    'var_lof_amax' => optional(optional($this->item->gempa)->lof)->amax ? $this->item->gempa->lof->amax : 0,
                    'var_lof_dmin' => optional(optional($this->item->gempa)->lof)->dmin ? $this->item->gempa->lof->dmin : 0,
                    'var_lof_dmax' => optional(optional($this->item->gempa)->lof)->dmax ? $this->item->gempa->lof->dmax : 0,
                    'var_tor' => optional(optional($this->item->gempa)->tor)->jumlah ? $this->item->gempa->tor->jumlah : 0,
                    'var_tor_amin' => optional(optional($this->item->gempa)->tor)->amin ? $this->item->gempa->tor->amin : 0,
                    'var_tor_amax' => optional(optional($this->item->gempa)->tor)->amax ? $this->item->gempa->tor->amax : 0,
                    'var_tor_dmin' => optional(optional($this->item->gempa)->tor)->dmin ? $this->item->gempa->tor->dmin : 0,
                    'var_tor_dmax' => optional(optional($this->item->gempa)->tor)->dmax ? $this->item->gempa->tor->dmax : 0,
                    'var_hrm' => optional(optional($this->item->gempa)->hrm)->jumlah ? $this->item->gempa->hrm->jumlah : 0,
                    'var_hrm_amin' => optional(optional($this->item->gempa)->hrm)->amin ? $this->item->gempa->hrm->amin : 0,
                    'var_hrm_amax' => optional(optional($this->item->gempa)->hrm)->amax ? $this->item->gempa->hrm->amax : 0,
                    'var_hrm_dmin' => optional(optional($this->item->gempa)->hrm)->dmin ? $this->item->gempa->hrm->dmin : 0,
                    'var_hrm_dmax' => optional(optional($this->item->gempa)->hrm)->dmax ? $this->item->gempa->hrm->dmax : 0,
                    'var_tre' => optional(optional($this->item->gempa)->tre)->jumlah ? $this->item->gempa->tre->jumlah : 0,
                    'var_tre_amin' => optional(optional($this->item->gempa)->tre)->amin ? $this->item->gempa->tre->amin : 0,
                    'var_tre_amax' => optional(optional($this->item->gempa)->tre)->amax ? $this->item->gempa->tre->amax : 0,
                    'var_tre_dmin' => optional(optional($this->item->gempa)->tre)->dmin ? $this->item->gempa->tre->dmin : 0,
                    'var_tre_dmax' => optional(optional($this->item->gempa)->tre)->dmax ? $this->item->gempa->tre->dmax : 0,
                    'var_mtr' => optional(optional($this->item->gempa)->mtr)->jumlah ? $this->item->gempa->mtr->jumlah : 0,
                    'var_mtr_amin' => optional(optional($this->item->gempa)->mtr)->amin ? $this->item->gempa->mtr->amin : 0,
                    'var_mtr_amax' => optional(optional($this->item->gempa)->mtr)->amax ? $this->item->gempa->mtr->amax : 0,
                    'var_mtr_adom' => optional(optional($this->item->gempa)->mtr)->adom ? $this->item->gempa->mtr->adom : 0,
                    'var_hbs' => optional(optional($this->item->gempa)->hbs)->jumlah ? $this->item->gempa->hbs->jumlah : 0,
                    'var_hbs_amin' => optional(optional($this->item->gempa)->hbs)->amin ? $this->item->gempa->hbs->amin : 0,
                    'var_hbs_amax' => optional(optional($this->item->gempa)->hbs)->amax ? $this->item->gempa->hbs->amax : 0,
                    'var_hbs_dmin' => optional(optional($this->item->gempa)->hbs)->dmin ? $this->item->gempa->hbs->dmin : 0,
                    'var_hbs_dmax' => optional(optional($this->item->gempa)->hbs)->dmax ? $this->item->gempa->hbs->dmax : 0,
                    'var_gug' => optional(optional($this->item->gempa)->gug)->jumlah ? $this->item->gempa->gug->jumlah : 0,
                    'var_gug_amin' => optional(optional($this->item->gempa)->gug)->amin ? $this->item->gempa->gug->amin : 0,
                    'var_gug_amax' => optional(optional($this->item->gempa)->gug)->amax ? $this->item->gempa->gug->amax : 0,
                    'var_gug_dmin' => optional(optional($this->item->gempa)->gug)->dmin ? $this->item->gempa->gug->dmin : 0,
                    'var_gug_dmax' => optional(optional($this->item->gempa)->gug)->dmax ? $this->item->gempa->gug->dmax : 0,
                    'var_gug_rmin' => optional(optional($this->item->gempa)->gug)->dmin ? $this->item->gempa->gug->dmin : 0,
                    'var_gug_rmax' => optional(optional($this->item->gempa)->gug)->dmax ? $this->item->gempa->gug->dmax : 0,
                    'var_gug_alun' => optional(optional($this->item->gempa)->gug)->arah ? implode(', ',$this->item->gempa->gug->arah) : ' ',
                    'var_apg' => optional(optional($this->item->gempa)->apg)->jumlah ? $this->item->gempa->apg->jumlah : 0,
                    'var_apg_amin' => optional(optional($this->item->gempa)->apg)->amin ? $this->item->gempa->apg->amin : 0,
                    'var_apg_amax' => optional(optional($this->item->gempa)->apg)->amax ? $this->item->gempa->apg->amax : 0,
                    'var_apg_dmin' => optional(optional($this->item->gempa)->apg)->dmin ? $this->item->gempa->apg->dmin : 0,
                    'var_apg_dmax' => optional(optional($this->item->gempa)->apg)->dmax ? $this->item->gempa->apg->dmax : 0,
                    'var_apg_rmin' => optional(optional($this->item->gempa)->apg)->dmin ? $this->item->gempa->apg->dmin : 0,
                    'var_apg_rmax' => optional(optional($this->item->gempa)->apg)->dmax ? $this->item->gempa->apg->dmax : 0,
                    'var_apg_alun' => optional(optional($this->item->gempa)->apg)->arah ? implode(', ',$this->item->gempa->apg->arah) : ' ',
                    'var_apl' => optional(optional($this->item->gempa)->apl)->jumlah ? $this->item->gempa->apl->jumlah : 0,
                    'var_apl_amin' => optional(optional($this->item->gempa)->apl)->amin ? $this->item->gempa->apl->amin : 0,
                    'var_apl_amax' => optional(optional($this->item->gempa)->apl)->amax ? $this->item->gempa->apl->amax : 0,
                    'var_apl_dmin' => optional(optional($this->item->gempa)->apl)->dmin ? $this->item->gempa->apl->dmin : 0,
                    'var_apl_dmax' => optional(optional($this->item->gempa)->apl)->dmax ? $this->item->gempa->apl->dmax : 0,
                    'var_apl_rmin' => optional(optional($this->item->gempa)->apl)->dmin ? $this->item->gempa->apl->dmin : 0,
                    'var_apl_rmax' => optional(optional($this->item->gempa)->apl)->dmax ? $this->item->gempa->apl->dmax : 0,
                    'var_apl_alun' => optional(optional($this->item->gempa)->apl)->arah ? implode(', ',$this->item->gempa->apl->arah) : ' ',
                    'var_lts' => optional(optional($this->item->gempa)->lts)->jumlah ? $this->item->gempa->lts->jumlah : 0,
                    'var_lts_amin' => optional(optional($this->item->gempa)->lts)->amin ? $this->item->gempa->lts->amin : 0,
                    'var_lts_amax' => optional(optional($this->item->gempa)->lts)->amax ? $this->item->gempa->lts->amax : 0,
                    'var_lts_dmin' => optional(optional($this->item->gempa)->lts)->dmin ? $this->item->gempa->lts->dmin : 0,
                    'var_lts_dmax' => optional(optional($this->item->gempa)->lts)->dmax ? $this->item->gempa->lts->dmax : 0,
                    'var_lts_tmin' => optional(optional($this->item->gempa)->lts)->tmin ? $this->item->gempa->lts->tmin : 0,
                    'var_lts_tmax' => optional(optional($this->item->gempa)->lts)->tmax ? $this->item->gempa->lts->tmax : 0,
                    'var_lts_wasap' => optional(optional($this->item->gempa)->lts)->wasap ? implode(', ',$this->item->gempa->lts->wasap) : ' ',
                    'var_gtb' => optional(optional($this->item->gempa)->gtb)->jumlah ? $this->item->gempa->gtb->jumlah : 0,
                    'var_gtb_amin' => optional(optional($this->item->gempa)->gtb)->amin ? $this->item->gempa->gtb->amin : 0,
                    'var_gtb_amax' => optional(optional($this->item->gempa)->gtb)->amax ? $this->item->gempa->gtb->amax : 0,
                    'var_gtb_dmin' => optional(optional($this->item->gempa)->gtb)->dmin ? $this->item->gempa->gtb->dmin : 0,
                    'var_gtb_dmax' => optional(optional($this->item->gempa)->gtb)->dmax ? $this->item->gempa->gtb->dmax : 0,
                    'var_trs' => optional(optional($this->item->gempa)->trs)->jumlah ? $this->item->gempa->trs->jumlah : 0,
                    'var_trs_amin' => optional(optional($this->item->gempa)->trs)->amin ? $this->item->gempa->trs->amin : 0,
                    'var_trs_amax' => optional(optional($this->item->gempa)->trs)->amax ? $this->item->gempa->trs->amax : 0,
                    'var_trs_spmin' => optional(optional($this->item->gempa)->trs)->spmin ? $this->item->gempa->trs->spmin : 0,
                    'var_trs_spmax' => optional(optional($this->item->gempa)->trs)->spmax ? $this->item->gempa->trs->spmax : 0,
                    'var_trs_dmin' => optional(optional($this->item->gempa)->trs)->dmin ? $this->item->gempa->trs->dmin : 0,
                    'var_trs_dmax' => optional(optional($this->item->gempa)->trs)->dmax ? $this->item->gempa->trs->dmax : 0,
                    'var_trs_skalamin' => optional(optional($this->item->gempa)->trs)->skala ? array_first($this->item->gempa->trs->skala) : 'NIHIL',
                    'var_trs_skalamax' => optional(optional($this->item->gempa)->trs)->skala ? array_last($this->item->gempa->trs->skala) : 'NIHIL',
                    'var_ketlain' => optional($this->item->keterangan)->deskripsi ? $this->item->keterangan->deskripsi : 'Nihil',
                    'var_rekom' => $this->item->rekomendasi->rekomendasi,
                    'var_nip_pelapor' => $this->item->nip_pelapor,
                    'var_nama_pelapor' => $this->item->user->name,
                    'var_data_date' => $this->item->var_data_date->format('Y-m-d'),
                    'var_nip_pemeriksa_pj' => $this->item->pj->isEmpty() ? ' ' : $this->item->pj->first()->user->nip,
                    'var_nama_pemeriksa_pj' => $this->item->pj->isEmpty() ? ' ' : $this->item->pj->first()->user->name,
                    'var_nip_pemeriksa' => optional($this->item->verifikator)->user ? $this->item->verifikator->user->nip : ' ',
                    'var_nama_pemeriksa' => optional($this->item->verifikator)->user ? $this->item->verifikator->user->name : ' ',
                ]
            );

            $this->data = $update ? true : false;
            return $this; 
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

    }

}
