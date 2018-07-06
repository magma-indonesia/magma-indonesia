<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\SigertanCrs;
use App\SigertanCrsDevices;
use App\SigertanCrsValidasi;
use App\v1\GertanCrs as OldCrs;
use App\Traits\ImportHelper;
use Indonesia;

class ImportCrs extends Import
{

    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);

        $this->old = OldCrs::whereBetween('idx',[$this->startNo('crs'),$this->endNo('crs')])->get();

        $this->mapping = $this->old->map(function ($item,$key) {
            $item->crs_pho = strlen($item->crs_pho) >14 
                ? substr($item->crs_pho, 0, 14) 
                : $item->crs_pho;

            $item->crs_typ = $item->crs_typ == 'GUNUNGAPI' ? 'GUNUNG API': $item->crs_typ;
            $item->crs_typ = $item->crs_typ == 'GEMPABUMI' ? 'GEMPA BUMI': $item->crs_typ;

            $item->crs_prv = Indonesia::search($item->crs_prv)->allProvinces()->first()->id;
            $item->crs_cty = Indonesia::search($item->crs_cty)->allCities()->first()->id;
            $item->crs_rgn = Indonesia::search($item->crs_rgn)->allDistricts()->first()->id;
            $item->crs_vil = Indonesia::search($item->crs_vil)->allVillages()->first()->id;

            $item->crs_lat = $item->crs_lat > 1000000 ? $item->crs_lat/1000 : $item->crs_lat;
            $item->crs_lon = $item->crs_lon > 50000 ? $item->crs_lon/100 : $item->crs_lon;

            $item->crs_brd = $item->crs_brd == 'TIDAK' ? 0 : 1;
            $item->lat_usr = $item->lat_usr == null ? 0 : $item->lat_usr;
            $item->long_usr = $item->long_usr == null ? 0 : $item->long_usr;

            $item->crs_val = $item->crs_val == 'VALID' ? 1 : 0;
            return $item;
        });
    }

    public function __invoke()
    {
        $this->mapping->each(function ($item, $key) {
            $this->setItem($item)->createCrs();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'Data CRS', 'message' => 'Data CRS berhasil diperbarui', 'count' => SigertanCrs::count() ] 
                : [ 'success' => 0, 'text' => 'Data CRS', 'message' => 'Data CRS gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createCrs()
    {
        $no = $this->item->idx;

        try {
            $crs = SigertanCrs::firstOrCreate(
                [
                    'crs_id' => $this->item->crs_ids
                ],
                [
                    'name' => $this->item->crs_usr,
                    'phone' => $this->item->crs_pho,
                    'waktu_kejadian' => $this->item->crs_dtm,
                    'zona' => $this->item->crs_zon,
                    'type' => $this->item->crs_typ,
                    'province_id' => $this->item->crs_prv,
                    'city_id' => $this->item->crs_cty,
                    'district_id' => $this->item->crs_rgn,
                    'village_id' => $this->item->crs_vil,
                    'bwd' => $this->item->crs_bwd,
                    'latitude' => $this->item->crs_lat,
                    'longitude' => $this->item->crs_lon,
                    'brd' => $this->item->crs_brd,
                    'sumber' => $this->item->crs_fsr,
                    'tsc' => $this->item->crs_tsc,
                    'ksc' => $this->item->crs_ksc,
                    'status' => $this->item->crs_sta,
                    'latitude_user' => $this->item->lat_usr,
                    'longitde_user' => $this->item->long_usr
                ]
            );

            $create = SigertanCrsDevices::firstOrCreate(
                [
                    'crs_id' => $crs->id
                ],
                [
                    'aplikasi' => $this->item->crs_soa,
                    'devices' => $this->item->crs_dvc,
                ]
            );

            if (!empty($this->item->crs_vor)) {
                $create = SigertanCrsValidasi::firstOrCreate(
                    [ 
                        'crs_id' => $crs->id
                    ],
                    [
                        'valid' => $this->item->crs_val,
                        'nip_id' => $this->item->crs_vor
                    ]
                );
            }

            if ($crs) {
                $this->data = $this->tempTable('crs',$no);
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }
}