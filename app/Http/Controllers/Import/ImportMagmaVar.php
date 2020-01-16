<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\VarPj;
use App\VarVerifikator;
use App\VarKeteranganLain;
use App\VarRekomendasi;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;

class ImportMagmaVar extends Import
{
    use ImportHelper;

    protected $obscode, $noticenumber, $pj, $verifikator, $lainnya;
    protected $start_no;
    protected $end_no;

    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 1200);
    }

    public function import(Request $request)
    {
        $this->start_no = $request->has('start') ? $request->start : $this->startNo('vars');
        $this->end_no = $request->has('end') ? $request->end : $this->endNo('var');

        $empty_vars = MagmaVar::where('var_nip_pelapor','')->get();
        $empty_vars->each(function ($var) {
            $var->delete();
        });

        $this->old = OldVar::select(
            'no','ga_code','var_noticenumber','ga_nama_gapi',
            'cu_status','var_issued','var_data_date',
            'periode','var_perwkt','var_source','var_nip_pelapor',
            'var_nip_pemeriksa_pj','var_nip_pemeriksa','var_log','var_ketlain')
        ->whereBetween('no',[$this->start_no,$this->end_no])
        ->orderBy('no');

        $this->rekomendasi = VarRekomendasi::all();

        $this->old->chunk(5000, function ($items) {
            foreach ($items as $key => $item) {
                if (filled($item->var_data_date)) {
                    $this->setItem($item)
                        ->createVar()
                        ->createPj()
                        ->createVerifikator()
                        ->createLainnya();
                }
            }
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars berhasil diperbarui', 'count' => MagmaVar::count(), 'others' =>  $this->start_no ] 
                    : [ 'success' => 0, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createVar()
    {
        $no = $this->item->no;
        $gacode = $this->item->ga_code;
        $var_nip_pelapor = $this->item->var_nip_pelapor;
        $this->pj = $this->item->var_nip_pemeriksa_pj == ' ' ? '' : $this->item->var_nip_pemeriksa_pj;
        $this->verifikator = $this->item->var_nip_pemeriksa == ' ' ? '' : $this->item->var_nip_pemeriksa;
        $var_source = $this->item->var_source;

        $var_nip_pelapor = empty($var_nip_pelapor) 
            ? '198803152015031005' : $var_nip_pelapor;
        $var_nip_pelapor = $var_nip_pelapor == '3273182505850001' 
            ? '3273182505850007' : $var_nip_pelapor;
        $var_nip_pelapor = $var_nip_pelapor == '196807071992051001' || $var_nip_pelapor == '196807071992031001' 
            ? '196807071992031018' : $var_nip_pelapor;

        $slug = 'laporan gunung api '.$this->item->ga_nama_gapi.' tanggal '.$this->item->var_data_date->format('Y-m-d').' periode '.$this->item->periode;

        $this->obscode = $this->obscode($gacode,$var_source);
        $this->noticenumber = $this->obscode.$this->item->var_noticenumber;

        $this->lainnya = $this->item->var_ketlain;

        try {
            $create = MagmaVar::updateOrCreate(
                [
                    'noticenumber'          => $this->noticenumber
                ],
                [
                    'slug'                  => str_slug($slug,'-'),
                    'code_id'               => $this->item->ga_code,
                    'var_data_date'         => $this->item->var_data_date,
                    'periode'               => $this->item->periode,
                    'var_perwkt'            => $this->item->var_perwkt,
                    'obscode_id'            => $this->obscode,
                    'status'                => $this->item->cu_status,
                    'rekomendasi_id'        => $this->rekomendasi->where('code_id',$this->item->ga_code)
                                                    ->where('status',$this->item->cu_status)->first()->id,
                    'nip_pelapor'           => $var_nip_pelapor,
                    'created_at'            => $this->item->var_issued,
                    'updated_at'            => $this->item->var_log
                ]
            );
     
            if ($create) {
                $this->data = $this->tempTable('vars',$no);
            }

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

    }

    protected function createPj()
    {
        if (!empty($this->pj)) {

            $create = VarPj::firstOrCreate(
                [
                    'noticenumber_id' => $this->noticenumber
                ],
                [
                    'nip_id' => $this->pj,
                ]
            );

        }

        return $this;
    }

    protected function createVerifikator()
    {
        if (!empty($this->verifikator)) {

            $data = VarVerifikator::firstOrCreate(
                [
                    'noticenumber_id' => $this->noticenumber
                ],
                [
                    'nip_id' => $this->verifikator,
                ]
            );

        }

        return $this;
    }

    protected function createLainnya()
    {
        if (!empty($this->lainnya)) {
            try {
                $create = VarKeteranganLain::firstOrCreate(
                    [
                        'noticenumber_id' => $this->noticenumber
                    ],
                    [
                        'deskripsi' => ucfirst($this->lainnya)
                    ]
                );

                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }            
        }
    }

}