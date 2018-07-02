<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TempTable;

trait ImportHelper
{
    /**     
     *   Start no dari magma_var v1
     *   
     * 
     */  
    protected function endNo($type)
    {
        if($type == 'var')
        {
            $end        = DB::connection('magma')
                        ->table('magma_var')
                        ->select('no')
                        ->orderBy('no','desc')
                        ->first();

            $end        = $end->no;
        }

        if($type == 'crs')
        {
            $end        = DB::connection('magma')
                        ->table('magma_crs')
                        ->select('idx AS no')
                        ->orderBy('no','desc')
                        ->first();

            $end        = $end->no;
        }

        if($type == 'vona')
        {
            $end        = DB::connection('magma')
                        ->table('ga_vona')
                        ->select('no')
                        ->orderBy('no','desc')
                        ->first();

            $end        = $end->no;
        }

        if($type == 'subs')
        {
            $end        = DB::connection('magma')
                        ->table('magma_subscribe')
                        ->select('no')
                        ->orderBy('no','desc')
                        ->first();

            $end        = $end->no;
        }

        if($type == 'ven')
        {
            $end        = DB::connection('magma')
                        ->table('magma_erupt')
                        ->select('erupt_id')
                        ->orderBy('erupt_id','desc')
                        ->first();

            $end        = $end->erupt_id;
        }

        if($type == 'roq')
        {
            $end        = DB::connection('magma')
                        ->table('magma_roq')
                        ->select('no')
                        ->orderBy('no','desc')
                        ->first();

            $end        = $end->no;
        }

        if($type == 'abs')
        {
            $end        = \App\v1\Absensi::select('id_abs')
                        ->orderBy('id_abs','desc')
                        ->first();

            $end        = $end->id_abs;
        }

        if($type == 'qls')
        {
            $end        = DB::connection('magma')
                        ->table('magma_sigertan')
                        ->select('qls_idx')
                        ->orderBy('qls_idx','desc')
                        ->first();

            $end        = $end->qls_idx;
        }

        if($type == 'qls_rec')
        {
            $end        = DB::connection('magma')
                        ->table('magma_qls_rec')
                        ->select('rec_idx')
                        ->orderBy('rec_idx','desc')
                        ->first();

            $end        = $end->rec_idx;
        }

        if($type == 'qls_atm')
        {
            $end        = DB::connection('magma')
                        ->table('magma_qls_atm')
                        ->select('atm_idx')
                        ->orderBy('atm_idx','desc')
                        ->first();

            $end        = $end->atm_idx;
        }

        if($type == 'qls_ver')
        {
            $end        = DB::connection('magma')
                        ->table('magma_qls_ver')
                        ->select('ver_idx')
                        ->orderBy('ver_idx','desc')
                        ->first();

            $end        = $end->ver_idx;
        }

        if($type == 'qls_sta')
        {
            $end        = DB::connection('magma')
                        ->table('magma_qls_trb')
                        ->select('trb_idx')
                        ->orderBy('trb_idx','desc')
                        ->first();

            $end        = $end->trb_idx;
        }

        if($type == 'qls_kej')
        {
            $end        = DB::connection('magma')
                        ->table('magma_qls_fst')
                        ->select('fst_idx')
                        ->orderBy('fst_idx','desc')
                        ->first();

            $end        = $end->fst_idx;
        }

        if($type == 'qls_sos')
        {
            $end        = DB::connection('magma')
                        ->table('magma_qls_sos')
                        ->select('sos_idx')
                        ->orderBy('sos_idx','desc')
                        ->first();

            $end        = $end->sos_idx;
        }
        
        return $end;

    }

    /**     
     *   Start no dari magma_var v1
     *   
     * 
     */  
    protected function startNo($kode)
    {
        $start      = TempTable::select('no')->where('jenis',$kode)->orderBy('id')->first();
                
        if (empty($start))
        {
            return $start = 1;
        } 

        return $start  = $start->no;

    }

    /**     
     *   Save for TempTable
     *   untuk import data
     *   @param string $kode gunung api, $no kolom no magma v1
     */  
    protected function tempTable($kode,$no)
    {
        $temp = TempTable::updateOrCreate(
                    [   'jenis' => $kode ],
                    [   'no' => $no      ]
                );
        
        return $temp;
    }

    /**     
     *   Fungsi Update Pos Pengamatan Gunungapi Merapi
     *   karena memiliki lebih dari 1 Pos
     *   @param string $ga_code, $code, $name
     * 
     */
    private function posMerapi($gacode,$source)
    {

        if (strpos($source, 'rakah') !== false)
        {
            $obscode    = $gacode.'1';
            return $obscode;
        }

        if (strpos($source, 'badan') !== false)
        {
            $obscode    = $gacode.'2';
            return $obscode;
        }

        if (strpos($source, 'urang') !== false)
        {
            $obscode    = $gacode.'3';
            return $obscode;
        }

        if (strpos($source, 'epos') !== false)
        {
            $obscode    = $gacode.'4';
            return $obscode;
        }

        if (strpos($source, 'elo') !== false)
        {
            $obscode    = $gacode.'5';
            return $obscode;
        }

        $obscode    = $gacode.'3';
        return $obscode;

    }

    /**     
     *   Update Pos Pengamatan Gunung Api
     *   disiapkan untuk Gunung Api yang memiliki lebih dari 1 pos
     * 
     *   @param string $gacode, $source
     * 
     */
    protected function obscode($gacode,$source)
    {
        
        if ($gacode!='MER')
        {

            $obscode        = $gacode.'1';
            return $obscode;

        }

        if ($gacode=='MER')
        {

            $obscode        = $this->posMerapi($gacode,$source);
            return $obscode;

        }

    }
}