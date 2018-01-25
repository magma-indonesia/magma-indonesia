<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait JenisGempa
{
    /**     
     *   Trait untuk nama dan jenis-jenis gempa
     *   
     *   @return object;
     * 
     */
    private function jenisgempa()
    {

        $namas      = ["Letusan/Erupsi","Awan Panas Letusan","Guguran","Awan Panas Guguran","Hembusan","Tremor Non-Harmonik","Tornillo","Low Frequency","Hybrid/Fase Banyak","Vulkanik Dangkal","Vulkanik Dalam","Very Long Period","Tektonik Lokal","Terasa","Tektonik Jauh","Double Event","Getaran Banjir","Harmonik","Deep Tremor","Tremor Menerus"];
        
        $kode       = ["lts","apl","gug","apg","hbs","tre","tor","lof","hyb","vtb","vta","vlp","tel","trs","tej","dev","gtb","hrm","dpt","mtr"];

        $sp         = ['tej','tel','dev','vta','hyb'];
		$normal     = ['vlp','dpt','vtb','lof','tor','hrm','tre','hbs','gtb'];
		$dominan    = ['mtr'];
		$luncuran   = ['gug','apg','apl'];
		$erupsi     = ['lts'];
        $terasa     = ['trs'];

        foreach ($namas as $i => $nama) 
        {
            $select = array();
            $name   = $nama;
            $code   = $kode[$i];

            if (in_array($code,$sp))
            {
    
                $jenis  = 'sp';
                $select = [
                    
                    'no',
                    'ga_code',
                    'var_noticenumber',
                    'var_source',
                    'var_'.$code,
                    'var_'.$code.'_amin',
                    'var_'.$code.'_amax',
                    'var_'.$code.'_spmin',
                    'var_'.$code.'_spmax',
                    'var_'.$code.'_dmin',
                    'var_'.$code.'_dmax'

                ];
    
            }
    
            if (in_array($code,$normal))
            {
    
                $jenis  = 'normal';
                $select = [
                    
                    'no',
                    'ga_code',
                    'var_noticenumber',
                    'var_source',
                    'var_'.$code,
                    'var_'.$code.'_amin',
                    'var_'.$code.'_amax',
                    'var_'.$code.'_dmin',
                    'var_'.$code.'_dmax'

                ];
    
            }
    
            if (in_array($code,$dominan))
            {
    
                $jenis  = 'dominan';
                $select = [
                    
                    'no',
                    'ga_code',
                    'var_noticenumber',
                    'var_source',
                    'var_'.$code,
                    'var_'.$code.'_amin',
                    'var_'.$code.'_amax',
                    'var_'.$code.'_adom'

                ];

            }
    
            if (in_array($code,$luncuran))
            {
    
                $jenis  = 'luncuran';
                $select = [
                    
                    'no',
                    'ga_code',
                    'var_noticenumber',
                    'var_source',
                    'var_'.$code,
                    'var_'.$code.'_amin',
                    'var_'.$code.'_amax',
                    'var_'.$code.'_dmin',
                    'var_'.$code.'_dmax',
                    'var_'.$code.'_rmin',
                    'var_'.$code.'_rmax',
                    'var_'.$code.'_alun'

                ];
    
            }
    
            if (in_array($code,$erupsi))
            {
    
                $jenis  = 'erupsi';
                $select = [
                    
                    'no',
                    'ga_code',
                    'var_noticenumber',
                    'var_source',
                    'var_'.$code,
                    'var_'.$code.'_amin',
                    'var_'.$code.'_amax',
                    'var_'.$code.'_dmin',
                    'var_'.$code.'_dmax',
                    'var_'.$code.'_tmin',
                    'var_'.$code.'_tmax',
                    'var_'.$code.'_wasap'

                ];

            }
    
            if (in_array($code,$terasa))
            {
    
                $jenis  = 'terasa';
                $select = [
                    
                    'no',
                    'ga_code',
                    'var_noticenumber',
                    'var_source',
                    'var_'.$code,
                    'var_'.$code.'_amin',
                    'var_'.$code.'_amax',
                    'var_'.$code.'_spmin',
                    'var_'.$code.'_spmax',
                    'var_'.$code.'_dmin',
                    'var_'.$code.'_dmax',
                    'var_'.$code.'_skalamin',
                    'var_'.$code.'_skalamax'

                ];
    
            }

            $gempa[]  = (object) [
                
                'nama'      => $name,
                'kode'      => $code,
                'jenis'     => $jenis,
                'select'    => $select
                
            ];

        };

        return $gempa;

    }
    
}