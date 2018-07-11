<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaRoq;
use App\RoqTanggapan;
use App\v1\MagmaRoq as OldRoq;
use App\Traits\ImportHelper;

class ImportRoq extends Import
{

    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldRoq::whereBetween('no',[$this->startNo('roq'),$this->endNo('roq')])->get();
        $this->old->each(function ($item,$key){
            $this->setItem($item)
                ->createRoq()
                ->createTanggapan();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'MAGMA-ROQ', 'message' => 'MAGMA-ROQ berhasil diperbarui', 'count' => MagmaRoq::count() ] 
                : [ 'success' => 0, 'text' => 'MAGMA-ROQ', 'message' => 'MAGMA-ROQ gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createRoq()
    {

        $mmi = $this->item->mmi == '-belum ada keterangan-' ? null : $this->item->mmi;

        try {
            $create = MagmaRoq::firstOrCreate(
                [ 
                    'noticenumber' => $this->item->id_lap
                ],
                [
                    'utc' => $this->item->datetime_utc,
                    'magnitude' => $this->item->magnitude,
                    'type' => 'SR',
                    'depth' => $this->item->depth,
                    'latitude' => $this->item->lat_lima,
                    'longitude' => $this->item->lon_lima,
                    'area' => $this->item->area,
                    'kota_terdekat' => $this->item->koter,
                    'mmi' => $mmi,
                    'nearest_volcano' => $this->item->nearest_volcano
                ]
            );

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }

    protected function createTanggapan()
    {
        
        if ($this->item->roq_tanggapan == 'YA') {

            $no = $this->item->no;

            $nip_pelapor = (empty($this->item->roq_nip_pelapor)  AND !empty($this->item->roq_nip_pemeriksa))
                ? $this->item->roq_nip_pemeriksa
                : $this->item->roq_nip_pelapor;

            $maplink = str_replace('https://magma.vsi.esdm.go.id/img/roqfm/','',$this->item->roq_maplink);

            try {
                $create = RoqTanggapan::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->id_lap
                    ],
                    [
                        'judul' => $this->item->roq_title,
                        'tsunami' => $this->item->roq_tsu == 'YA'? 1 : 0 ,
                        'pendahuluan' => $this->item->roq_intro,
                        'kondisi_wilayah' => $this->item->roq_konwil,
                        'mekanisme' => $this->item->roq_mekanisme,
                        'dampak' => $this->item->roq_efek,
                        'rekomendasi' => $this->item->roq_rekom,
                        'sumber' => explode(';',$this->item->roq_source),
                        'maplink' => $maplink,
                        'nip_pelapor' => $nip_pelapor,
                        'nip_pemeriksa' => $this->item->roq_nip_pemeriksa,
                    ]
                );

                if ($create) {
                    $this->data = $this->tempTable('roq',$no);
                }

                return $this;
            }
    
            catch (Exception $e) {
                $this->sendError($e);
            }
        }

    }
} 