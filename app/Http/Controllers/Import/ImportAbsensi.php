<?php

namespace App\Http\Controllers\Import;

use App\Traits\ImportHelper;
use App\Absensi;
use App\v1\Absensi as OldAbsensi;

class ImportAbsensi extends Import
{

    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {

        $this->old = Absensi::count() ? 
                        OldAbsensi::whereBetween('date_abs',$this->lastDateAbsensi()) :
                        OldAbsensi::all();

        $this->old->each(function ($item, $key) {
            $this->setItem($item)->createAbsensi();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' => 'Absensi', 'message' => 'Data Absensi berhasil diperbarui','count' => Absensi::count(), 'others' => $this->lastDateAbsensi() ] 
                : [ 'success' => 0, 'text' => 'Absensi', 'message' => 'Data Absensi gagal diperbarui','count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function lastDateAbsensi()
    {
        return [
            Absensi::orderBy('checkin','desc')->first()->checkin->format('Y-m-d'),
            OldAbsensi::orderBy('date_abs','desc')->first()->date_abs,
        ];
    }

    protected function createAbsensi()
    {
        $no = $this->item->id_abs;
        $nip = $this->item->vg_nip;
        $kantor = $this->item->obscode;
        $code = $this->item->ga_code;
        $checkin = $this->item->checkin_time == '00:00:00' ?  null : $this->item->date_abs.' '.$this->item->checkin_time;
        $checkin_image = empty($this->item->checkin_image) ?  null : $this->item->checkin_image;
        $checkin_latitude = $this->item->checkin_lat;
        $checkin_longitude = $this->item->checkin_lon;
        $checkout = $this->item->checkout_time == '00:00:00' ? null : $this->item->date_abs.' '.$this->item->checkout_time;
        $checkout_image = empty($this->item->checkout_image) ? null  : $this->item->checkout_image;
        $checkout_latitude = $this->item->checkout_lat;
        $checkout_longitude = $this->item->checkout_lon;
        $distance = $this->item->checkin_dist;
        $duration = $this->item->length_work;
        $nip_ver = empty($this->item->nip_ver) ?  null : $this->item->nip_ver;
        $keterangan = $this->item->ket_abs;

        if ($checkin != null)
        {
            try {
                $create = Absensi::updateOrCreate(
                    [
                        'nip_id' => $nip,
                        'checkin' => $checkin,
                    ],
                    [
                        'kantor_id' => $kantor,
                        'checkin_image' => $checkin_image,
                        'checkin_latitude' => $checkin_latitude,
                        'checkin_longitude' => $checkin_longitude,
                        'checkin_distance' => $this->item->checkin_dist,
                        'checkout_image' => $checkout_image,
                        'checkout' => $checkout,
                        'checkout_latitude' => $checkout_latitude,
                        'checkout_longitude' => $checkout_longitude,
                        'distance' => $this->item->checkout_dist,
                        'duration' => $duration,
                        'nip_verifikator' => $nip_ver,
                        'keterangan' => $keterangan
                    ]
                );
    
                if ($create) {
                    $this->data = true;
                }
            }
            catch (Exception $e) {
                $this->sendError($e);
            }

        }

        return $this;
    }
}