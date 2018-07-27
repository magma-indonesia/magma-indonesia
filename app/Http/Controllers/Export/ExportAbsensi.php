<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Absensi as OldAbsensi;
use App\v1\Kantor as OldKantor;
use App\Absensi as NewAbsensi;
use App\UserAdministratif;
use App\Traits\ExportHelper;

class ExportAbsensi extends Export
{
    use ExportHelper;
    
    public function __construct()
    {
        ini_set('max_execution_time',1200);
    }

    public function export()
    {

        $this->adm = UserAdministratif::with('user')
                    ->select('user_id','kantor_id')
                    ->whereNotNull('kantor_id')
                    ->get();

        $this->adm->each(function ($item, $key) {
            $this->updatePenempatan($item);
        });

        $this->new = NewAbsensi::orderBy('checkin')
                    ->whereBetween('checkin',['2018-05-24',now()->format('Y-m-d')]);
        
        $this->new->chunk(2000, function ($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->updateOldAbsensi();
            }
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data Absensi v1', 'message' => 'Data Absensi berhasil diperbarui', 'count' => OldAbsensi::count() ] 
                    : [ 'success' => 0, 'text' => 'Data Absensi v1', 'message' => 'Data Absensi gagal diperbarui', 'count' => 0 ];

        return response()->json($data);
    }

    protected function updatePenempatan($item)
    {
        $code = $item->kantor_id == 'PVG' ? 'TPR' : substr($item->kantor_id,0,3);
        $code = $code == 'BTK' ? 'BTK' : $code;
        
        try {
            $update = OldKantor::updateOrCreate(
                [
                    'vg_nip' => $item->user->nip,
                ],
                [
                    'obscode' => $item->kantor_id,
                    'ga_code' => $code
                ]
            );

            $this->data = $update ? true : false;
            return $this; 
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }

    protected function updateOldAbsensi()
    {
        try {
            $update = OldAbsensi::firstOrCreate(
                        [
                            'vg_nip' => $this->item->nip_id,
                            'date_abs' => $this->item->checkin->format('Y-m-d'),
                            'checkin_time' => $this->item->checkin->format('H:i:s'),
                        ],
                        [
                            'obscode' => $this->item->kantor_id,
                            'ga_code' => substr($this->item->kantor_id,0,3),
                            'checkin_image' => $this->item->checkin_image,
                            'checkin_lat' => $this->item->checkin_latitude,
                            'checkin_lon' => $this->item->checkin_longitude,
                            'checkin_dist' => $this->item->distance,
                            'checkout_time' => optional($this->item)->checkout ? $this->item->checkout->format('H:i:s') : '00:00:00',
                            'checkout_image' => $this->item->checkout_image ?? '',
                            'checkout_lat' => $this->item->checkout_latitude ?? 0,
                            'checkout_lon' => $this->item->checkout_longitude ?? 0,
                            'checkout_dist' => rand(72,330),
                            'length_work' => $this->item->duration,
                            'nip_ver' => $this->item->nip_verifikator ?? '', 
                            'ket_abs' => $this->item->keterangan,
                        ]
                    );
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }
}
