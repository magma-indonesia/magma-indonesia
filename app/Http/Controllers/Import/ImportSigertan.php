<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaSigertan;
use App\SigertanGeologi;
use App\SigertanKerusakan;
use App\SigertanKondisi;
use App\SigertanRekomendasi;
use App\SigertanAnggotaTim;
use App\SigertanVerifikator;
use App\SigertanStatus;
use App\SigertanFotoKejadian;
use App\SigertanFotoSosialisasi;
use App\v1\MagmaSigertan as OldSigertan;
use App\v1\SigertanRekomendasi as OldSigertanRekomendasi;
use App\v1\SigertanAnggotaTim as OldSigertanTim;
use App\v1\SigertanVerifikator as OldSigertanVerifikator;
use App\v1\SigertanStatus as OldSigertanStatus;
use App\v1\SigertanFotoKejadian as OldSigertanKejadian;
use App\v1\SigertanFotoSosialisasi as OldSigertanSosialisasi;
use App\Traits\ImportHelper;

class ImportSigertan extends Import
{

    use ImportHelper;

    public function __construct()
    {
        $this->old = OldSigertan::whereBetween('qls_idx',[$this->startNo('qls'),$this->endNo('qls')])->get();

        $this->oldRekomendasi = OldSigertanRekomendasi::whereBetween('rec_idx',[$this->startNo('qls_rec'),$this->endNo('qls_rec')])->get();

        $this->oldTim = OldSigertanTim::whereBetween('atm_idx',[$this->startNo('qls_atm'),$this->endNo('qls_atm')])->get();

        $this->oldVerifikator = OldSigertanVerifikator::whereBetween('ver_idx',[$this->startNo('qls_ver'),$this->endNo('qls_ver')])->get();

        $this->oldStatus = OldSigertanStatus::whereBetween('trb_idx',[$this->startNo('qls_sta'),$this->endNo('qls_sta')])->get();

        $this->oldKejadian = OldSigertanKejadian::whereBetween('fst_idx',[$this->startNo('qls_kej'),$this->endNo('qls_kej')])->get();

        $this->oldSosialisasi = OldSigertanSosialisasi::whereBetween('sos_idx',[$this->startNo('qls_sos'),$this->endNo('qls_sos')])->get();
    }

    public function __invoke()
    {
        $this->old->each(function ($item,$key) {
            $this->setItem($item)->createSigertan();
        });

        $this->oldRekomendasi->each(function ($item,$key) {
            $this->setItem($item)
                ->sigertanExists()
                ->createRekomendasi();
        });

        $this->oldTim->each(function ($item,$key) {
            $this->setItem($item)
                ->sigertanExists()
                ->createTim();
        });

        $this->oldVerifikator->each(function ($item,$key) {
            $this->setItem($item)
                ->sigertanExists()
                ->createVerifikator();
        });

        $this->oldStatus->each(function ($item,$key) {
            $this->setItem($item)
                ->sigertanExists()
                ->createStatus();
        });

        $this->oldKejadian->each(function ($item,$key) {
            $this->setItem($item)
                ->sigertanExists()
                ->createKejadian();
        });

        $this->oldSosialisasi->each(function ($item,$key) {
            $this->setItem($item)
                ->sigertanExists()
                ->createSosialiasi();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'MAGMA-SIGERTAN', 'message' => 'MAGMA-SIGERTAN berhasil diperbarui', 'count' => MagmaSigertan::count() ] 
                : [ 'success' => 0, 'text' => 'MAGMA-SIGERTAN', 'message' => 'MAGMA-SIGERTAN gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function sigertanExists()
    {
        $exists = MagmaSigertan::where('noticenumber','like',$this->item->qls_ids)->exists();

        $this->qlsExists = $exists;
        return $this;
    }

    protected function createSigertan()
    {
        $no = $this->item->qls_idx;
        
        $lokasi = empty($this->item->qls_lok) ? null : $this->item->qls_lok;
        $geologi = empty($this->item->qls_geo) ? null : $this->item->qls_geo;
        $situasi = empty($this->item->qls_pst) ? null : $this->item->qls_pst;
        
        $nip_ketua = $this->item->qls_ktm == '196308231993031001' ? '196308231993061001' : $this->item->qls_ktm;
        $nip_ketua = $nip_ketua == '197307232006041002' ? '197307232006041001' : $nip_ketua;
        $nip_ketua = empty($nip_ketua) ? '196508231994031001' :  $nip_ketua;

        try {
            $create = MagmaSigertan::firstOrCreate(
                [
                    'noticenumber' => $this->item->qls_ids,
                    'crs_id' => $this->item->crs_ids
                ],
                [
                    'peta_lokasi' => $lokasi,
                    'peta_geologi' => $geologi,
                    'peta_situasi' => $situasi,
                    'disposisi' => $this->item->qls_dis,
                    'nip_ketua' => $nip_ketua,
                    'created_at' => $this->item->qls_tfn == '0000-00-00 00:00:00' ?  '2017-01-01 00:00:00' : $this->item->qls_tfn,
                    'updated_at' => $this->item->qls_led
                ]
            );

            $create = SigertanGeologi::firstOrCreate(
                [
                    'noticenumber_id' => $this->item->qls_ids,
                ],
                [
                    'bentang_alam' => $this->item->qls_sba,
                    'kemiringan_lereng' => $this->item->qls_mrl,
                    'kemiringan_lereng_rata' => $this->item->qls_mra,
                    'ketinggian' => $this->item->qls_elv,
                    'jenis_batuan' => $this->item->qls_jbt,
                    'formasi_batuan' => $this->item->qls_frm,
                    'struktur_geologi' => $this->item->qls_str,
                    'jenis_tanah' => $this->item->qls_jtp,
                    'ketebalan_tanah' => $this->item->qls_ktp,
                    'tipe_keairan' => $this->item->qls_air,
                    'muka_air_tanah' => $this->item->qls_dep,
                    'tata_guna_lahan' => $this->item->qls_tgl
                ]
            );

            $create = SigertanKondisi::firstOrCreate(
                [
                    'noticenumber_id' => $this->item->qls_ids,
                ],
                [
                    'prakiraan_kerentanan' => $this->item->qls_zkg,
                    'tipe_gerakan' => $this->item->qls_tgt,
                    'material' => $this->item->qls_mgt,
                    'arah_longsoran' => $this->item->qls_dir,
                    'panjang_total' => $this->item->qls_ptl,
                    'lebar_massa' => $this->item->qls_lmb,
                    'panjang_massa' => $this->item->qls_pmb,
                    'ketebalan_massa' => $this->item->qls_kmb,
                    'lebar_bidang' => $this->item->qls_lbl,
                    'panjang_bidang' => $this->item->qls_pbl,
                    'ketebalan_bidang' => $this->item->qls_kbl,
                    'faktor_penyebab' => $this->item->qls_cau
                ]
            );

            $create = SigertanKerusakan::firstOrCreate(
                [
                    'noticenumber_id' => $this->item->qls_ids,
                ],
                [
                    'meninggal' => $this->item->qls_kmd,
                    'luka' => $this->item->qls_kll,
                    'rumah_rusak' => $this->item->qls_rrk,
                    'rumah_hancur' => $this->item->qls_rhc,
                    'rumah_terancam' => $this->item->qls_rtr,
                    'bangunan_rusak' => $this->item->qls_blr,
                    'bangunan_hancur' => $this->item->qls_blh,
                    'bangunan_terancam' => $this->item->qls_bla,
                    'lahan_rusak' => $this->item->qls_llp,
                    'jalan_rusak' => $this->item->qls_pjr
                ]
            );

            if ($create) {
                $this->data = $this->tempTable('qls',$no);
            }

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }

    protected function createRekomendasi()
    {
        if ($this->qlsExists) {
            try {
                $no = $this->item->rec_idx;
                $create = SigertanRekomendasi::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->qls_ids,
                    ],
                    [
                        'rekomendasi' => $this->item->qls_rec
                    ]
                );

                if ($create) {
                    $this->data = $this->tempTable('qls_rec',$no);
                }

                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        }
    }

    protected function createTim()
    {
        if ($this->qlsExists   ) {
            try {
                $no = $this->item->atm_idx;
                $create = SigertanAnggotaTim::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->qls_ids,
                        'nip_id' => $this->item->qls_atm
                    ],
                    [
                        
                    ]
                );
    
                if ($create) {
                    $this->data = $this->tempTable('qls_atm',$no);
                }

                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        }
    }

    protected function createVerifikator()
    {
        if ($this->qlsExists) {
            try {
                $no = $this->item->ver_idx;
                $create = SigertanVerifikator::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->qls_ids,
                    ],
                    [
                        'nip_id' => $this->item->qls_ver
                    ]
                );
    
                if ($create) {
                    $this->data = $this->tempTable('qls_ver',$no);
                }
    
                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        } 
    }

    protected function createStatus()
    {
        if ($this->qlsExists) {
            try {
                $no = $this->item->trb_idx;
                $create = SigertanStatus::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->qls_ids,
                    ],
                    [
                        'nip_penerbit' => $this->item->qls_trb,
                        'status' => $this->item->trb_act == 'TERBIT' ? 1 : 0,
                    ]
                );
    
                if ($create) {
                    $this->data = $this->tempTable('qls_sta',$no);
                }
    
                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        }
    }

    protected function createKejadian()
    {
        if ($this->qlsExists) {
            try {
                $no = $this->item->fst_idx;
                $create = SigertanFotoKejadian::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->qls_ids,
                        'filename' => $this->item->qls_fst
                    ],
                    [
                        
                    ]
                );
    
                if ($create)
                {
                    $this->data = $this->temptable('qls_kej',$no);
                }

                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        }
    }

    protected function createSosialiasi()
    {
        if ($this->qlsExists) {
            try {
                $no = $this->item->sos_idx;
                $create = SigertanFotoSosialisasi::firstOrCreate(
                    [
                        'noticenumber_id' => $this->item->qls_ids,
                        'filename' => $this->item->qls_sos,
                    ],
                    [
                        
                    ]
                );

                if ($create) {
                    $this->data = $this->temptable('qls_sos',$no);
                }

                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        }
    }
}