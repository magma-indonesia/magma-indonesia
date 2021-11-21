<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class MagmaSigertanShowResource extends JsonResource
{
    protected function signedUrl()
    {
        return URL::signedRoute('v1.gertan.sigertan.show', ['id' => $this->crs_ids]);
    }

    protected function deskripsi()
    {
        return 'Gerakan tanah terjadi di ' . $this->crs_vil . ', ' . $this->crs_rgn . ', ' . $this->crs_cty . ', ' . $this->crs_prv . ' pada ' . $this->crs_dtm->formatLocalized('%A, %d %B %Y') . ' pukul ' . $this->crs_dtm->format('H:i:s') . ' ' . $this->crs_zon . '. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi ' . $this->crs_lat . ' LU dan ' . $this->crs_lon . ' BT.';
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pelapukan = $this->tanggapan->qls_jtp ? 'Jenis Pelapukan berupa ' . $this->tanggapan->qls_jtp.'.' : '';
        $struktur = $this->tanggapan->qls_str ? ' Struktur berupa ' . $this->tanggapan-> qls_str . '.' : '';
        $kedalaman_air = $this->tanggapan->qls_dep ? ' dengan kedalaman air tanah sekitar ' . $this->tanggapan->qls_dep . ' meter di bawah permukaan.' : '.';

        return [
            'laporan' => [
                'id' => $this->crs_ids,
                'peta' => empty($this->tanggapan->qls_pst) ? null : $this->tanggapan->qls_pst,
                'pelapor' => $this->crs_usr,
                'judul' => 'Laporan Tanggapan Gerakan Tanah di ' . $this->crs_vil . ', ' . $this->crs_rgn . ', ' . $this->crs_cty . ', ' . $this->crs_prv,
                'updated_at' => 'Diperbarui pada tanggal ' . $this->crs_log->formatLocalized('%d %B %Y') . ' pukul ' . $this->crs_log->format('H:i:s') . ' WIB',
                'tingkat_kerentanan' => $this->tanggapan->qls_zkg,
                'deskripsi' => $this->deskripsi(),
                'foto_sosialisasi' => $this->tanggapan->foto_sosialisasi->isEmpty() ? null : $this->tanggapan->foto_sosialisasi->pluck('qls_sos'),
                'foto_kejadian' =>  $this->tanggapan->foto_kejadian->isEmpty() ? null: $this->tanggapan->foto_kejadian->pluck('qls_fst'),
            ],
            'tanggapan' => [
                'tipe' => empty($this->tanggapan->qls_tgt) ? null : $this->tanggapan->qls_tgt,
                'dampak' => [
                    'korban' => $this->tanggapan->qls_kmd,
                    'luka' => $this->tanggapan->qls_kll,
                    'rumah_rusak' => $this->tanggapan->qls_rrk,
                    'rumah_hancur' => $this->tanggapan->qls_rhc,
                    'rumah_terancam' => $this->tanggapan->qls_rtr,
                    'bangunan_rusak' => $this->tanggapan->qls_blr,
                    'bangunan_hancur' => $this->tanggapan->qls_blh,
                    'bangunan_terancam' => $this->tanggapan->qls_bla,
                    'lahan_rusak' => $this->tanggapan->qls_llp,
                    'jalan_rusak' => $this->tanggapan->qls_pjr,
                ],
                'kondisi' => [
                    'morfologi' => empty($this->tanggapan->qls_sba) ? null : 'Secara umum lokasi gerakan tanah ini merupakan daerah ' . strtolower(implode(', ', $this->tanggapan->qls_sba)) . ' yang memiliki kemiringan lereng ' . implode(', ', $this->tanggapan->qls_mrl),
                    'geologi' => empty($this->tanggapan->qls_frm) ? null : 'Berdasarkan peta geologi, lokasi bencana tersusun oleh formasi ' . $this->tanggapan->qls_frm . '. Jenis Batuan di antaranya adalah ' . $this->tanggapan->qls_jbt . '. '.$pelapukan.$struktur,
                    'keairan' => empty($this->tanggapan->qls_air) ? null : 'Keairan di lokasi gerakan tanah berupa ' . implode(', ', $this->tanggapan->qls_air) . $kedalaman_air,
                    'tata_guna_lahan' => empty($this->tanggapan->qls_tgl) ? null : 'Tata Guna Lahan  di lokasi gerakan tanah ini berupa ' . implode(', ', $this->tanggapan->qls_tgl) . '.',
                    'kerentanan' => empty($this->tanggapan->qls_zkg) ? null : 'Berdasarkan Peta Potensi Gerakan Tanah yang dikeluarkan Badan Geologi, Pusat Vulkanologi dan Mitigasi Bencana Geologi pada bulan ini, lokasi bencana berada pada Zona Potensi Gerakan Tanah ' . str_replace_last(', ', ' hingga ', title_case(implode(', ', $this->tanggapan->qls_zkg))) . '. Yang artinya daerah ini memiliki potensi ' . strtolower(str_replace_last(', ', ' hingga ', implode(', ', $this->tanggapan->qls_zkg))) . ' untuk terjadi gerakan tanah.',
                    'penyebab' => empty($this->tanggapan->qls_cau) ? null : title_case(implode('<br>', $this->tanggapan->qls_cau))
                ]
            ],
            'rekomendasi' => [
                empty($this->tanggapan->rekomendasi) ? null : nl2br($this->tanggapan->rekomendasi->qls_rec)
            ],
            'share' => [
                'url' => $this->signedUrl(),
                'description' => $this->deskripsi(),
            ]
        ];
    }
}
