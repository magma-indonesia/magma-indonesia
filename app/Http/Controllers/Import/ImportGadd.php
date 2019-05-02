<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Gadd;
use App\v1\Gadd as OldDd;
use App\PosPga;
use App\Kantor;

class ImportGadd extends Import
{

    protected $code,$obscode,$name;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {

        $this->old = OldDd::orderBy('no')->get();
        $this->old->each(function ($item, $key) {
            $this->setItem($item)->createGadd();
        });

        $this->setNew(Gadd::get());

        $this->new->each(function ($item, $key) {

            $obscodes = $item->code != 'MER'
                ? [ '1' => 'Pos Pengamatan Gunung Api '.$item->name]
                : [ '1' => 'Pos Pengamatan Gunung Merapi - Jarakah',
                    '2' => 'Pos Pengamatan Gunung Merapi - Babadan',
                    '3' => 'Pos Pengamatan Gunung Merapi - Kaliurang',
                    '4' => 'Pos Pengamatan Gunung Merapi - Ngepos',
                    '5' => 'Pos Pengamatan Gunung Merapi - Selo'
                ];

            $obscodes = $item->code != 'AGU'
                ? $obscodes
                : [ '1' => 'Pos Pengamatan Gunung Agung - Rendang',
                    '2' => 'Pos Pengamatan Gunung Agung - Batulompeh',
                ];

            $obscodes = $item->code != 'KRA'
                ? $obscodes
                : [ '1' => 'Pos Pengamatan Gunung Anak Krakatau - Pasaruan',
                    '2' => 'Pos Pengamatan Gunung Anak Krakatau - Kalianda',
                ];

            foreach ($obscodes as $number => $name) {
                $this->setCode($item->code)
                    ->setObscode($number)
                    ->setName($name)
                    ->createKantor()
                    ->updateKantor()
                    ->createPos();
            }

        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'Data Dasar', 'message' => 'Data Dasar berhasil diperbarui', 'count' => Gadd::count() ] 
                : [ 'success' => 0, 'text' => 'Data Dasar', 'message' => 'Data Dasar gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    protected function setObscode($number)
    {
        $this->obscode = $this->code.$number;
        return $this;
    }

    protected function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    protected function createGadd()
    {
        try {
            $create = Gadd::updateOrCreate(
                [   
                    'code'              => $this->item->ga_code
                ],
                [   
                    'name'              => $this->item->ga_nama_gapi,
                    'alias'             => $this->item->ga_alias_gapi,
                    'tzone'             => $this->item->ga_tzone,
                    'zonearea'          => $this->item->ga_zonearea,
                    'district'          => $this->item->ga_kab_gapi,
                    'province'          => $this->item->ga_prov_gapi,
                    'nearest_city'      => $this->item->ga_koter_gapi,
                    'division'          => $this->item->ga_wil_gapi,
                    'volc_type'         => $this->item->ga_tipe_gapi,
                    'elevation'         => $this->item->ga_elev_gapi,
                    'latitude'          => $this->item->ga_lat_gapi,
                    'longitude'         => $this->item->ga_lon_gapi,
                    'smithsonian_id'    => $this->item->ga_id_smithsonian
                ]
            );

            if ($create) {
                $this->data = true;
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }

    protected function createPos()
    {
        try {
            $create = PosPga::firstOrCreate(
                [   
                    'obscode'  => $this->obscode
                ],
                [   
                    'code_id'     => $this->code,
                    'observatory' => $this->name,
                    'address'     => $this->old->firstWhere('ga_code',$this->code)->ga_adr_pos,
                    'elevation'   => $this->old->firstWhere('ga_code',$this->code)->ga_elev_pos,
                    'latitude'    => $this->old->firstWhere('ga_code',$this->code)->ga_lat_pos,
                    'longitude'   => $this->old->firstWhere('ga_code',$this->code)->ga_lon_pos
                ]
            );

            if ($create) {
                $this->data = true;
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }

    protected function createKantor()
    {
        try {
            $create = Kantor::firstOrCreate(
                [   
                    'code'  => $this->obscode
                ],
                [
                    'nama' => $this->name,
                    'tzone' => $this->old->firstWhere('ga_code',$this->code)->ga_zonearea,
                    'address' => $this->old->firstWhere('ga_code',$this->code)->ga_adr_pos,
                    'elevation' => $this->old->firstWhere('ga_code',$this->code)->ga_elev_pos,
                    'latitude' => $this->old->firstWhere('ga_code',$this->code)->ga_lat_pos,
                    'longitude' => $this->old->firstWhere('ga_code',$this->code)->ga_lon_pos
                ]
            );

            if ($create) {
                $this->data = true;
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }

    protected function updateKantor()
    {
        try {
            $update = Kantor::firstOrCreate(
                [
                    'code' => 'PVG'
                ],
                [
                    'nama' => 'Pusat Vulkanologi dan Mitigasi Bencana Geologi',
                    'tzone' => 'WIB',
                    'address' => 'Jl. Diponegoro No. 57 Bandung',
                    'elevation' => 735,
                    'latitude' => -6.899733,
                    'longitude' => 107.620427
                ]
            );
    
            $update = Kantor::firstOrCreate(
                [
                    'code' => 'BTK'
                ],
                [
                    'nama' => 'Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi',
                    'tzone' => 'WIB',
                    'address' => 'Jl. Cendana No. 15 Yogyakarta',
                    'elevation' => 110,
                    'latitude' => -7.797772,
                    'longitude' => 110.384899
                ]
            );

            if ($update) {
                $this->data = true;
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }
}