<?php

namespace App\Http\Controllers\Exports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Vona as OldVona;
use App\Vona as NewVona;

use App\Traits\ExportHelper;

class ExportVona extends Export
{

    public function __construct()
    {
        ini_set('max_execution_time',1200);
    }

    public function export()
    {
        $this->new = NewVona::with('gunungapi','user')->orderBy('issued')
                    ->whereBetween('issued',['2018-05-24',now()->format('Y-m-d')]);

        $this->new->chunk(500, function($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->updateOldVona();
            }
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data VONA v1', 'message' => 'Data VONA berhasil diperbarui', 'count' => OldVona::count()]
                    : [ 'success' => 0, 'text' => 'Data VONA v1', 'message' => 'Data VONA gagal diperbarui', 'count' => 0 ];

        return response()->json($data);

    }

    protected function updateOldVona()
    {
        $height = 'Best estimate of ash-cloud top is around '.round($this->item->vch_asl*3.3).' FT ('. $this->item->vch_asl .' M) above sea level, may be higher than what can be observed clearly. Source of height data: ground observer.';

        $contacts = 'Center for Volcanology and Geological Hazard Mitigation (CVGHM)
        Tel: +62-22-727-2606
        Facsimile: +62-22-720-2761
        Email : vsi@vsi.esdm.go.id';

        $notice = 'A new VONA will be issued if conditions change significantly or the colour code is changes.<br>Latest Volcanic information is posted at <strong>VONA | MAGMA Indonesia</strong> Website<br>Link : <a href="https://magma.vsi.esdm.go.id/vona/">https://magma.vsi.esdm.go.id/vona/</a>';

        try {
            $update = OldVona::updateOrCreate(
                [
                    'notice_number' => $this->item->noticenumber
                ],
                [
                    'issued' => $this->item->issued_utc,
                    'issued_time' => $this->item->issued,
                    'type' => $this->item->type,
                    'ga_nama_gapi' => $this->item->gunungapi->name,
                    'ga_id_smithsonian' => $this->item->gunungapi->smithsonian_id,
                    'ga_code' => $this->item->code_id,
                    'cu_avcode' => $this->item->cu_code,
                    'pre_avcode' => $this->item->prev_code,
                    'source' => $this->item->gunungapi->name.' Volcano Observatory',
                    'volcano_location' => $this->item->location,
                    'area' => $this->item->gunungapi->province,
                    'summit_elevation' => $this->item->gunungapi->elevation,
                    'volcanic_act_summ' => $this->item->vas,
                    'vc_height' => $this->item->vch_asl,
                    'vc_height_text' => $height,
                    'other_vc_info' => $this->item->vch_other,
                    'remarks' => optional($this->item)->remarks ? $this->item->remarks : '-',
                    'contacts' => $contacts,
                    'next_notice' => $notice,
                    'sent' => $this->item->sent ? '1' : '0',
                    'nip' => $this->item->nip_pelapor,
                    'nama' => $this->item->user->name,
                    'sender' => optional($this->item->pengirim)->name ? $this->item->pengirim->name : null,
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
