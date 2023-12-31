<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PosPga;
use App\TempTable;
use App\Import;
use App\Kantor;
use App\MagmaSigertan;
use App\VarPj;
use App\VarVerifikator;

use App\Notifications\ImportNotification;

trait MagmaHelper
{

    private function sigertanExists($noticenumber)
    {
        $exists = MagmaSigertan::where('noticenumber','like',$noticenumber)->exists();

        return $exists;
    }

    /**     
     *   Untuk mengirim notifikasi ke Slack
     *   @param string $type jenis notifikasi
     * 
     */  
    private function sendNotif($type)
    {
        try {
            $import = new Import();
            $import->notify(new ImportNotification($type));
        }
        catch (Exception $e){

        }
    }

    /**     
     *   Import data status gunung api
     *   @param string $status gunung api
     * 
     */  
    private function getStatus($status)
    {
        switch ($status){
            case 'Level I (Normal)':
                return 1;
            case 'Level II (Waspada)':
                return 2;
            case 'Level III (Siaga)':
                return 3;
            default:
                return 4;
        }
    }

    /**     
     *   Bidang dari masing-masing user
     *   
     * 
     */  
    protected function bidangConvert($bidang)
    {
        if (empty($bidang) || strlen($bidang)<3 )
        {
            return 5;
        }

        if (str_contains($bidang,'kanologi'))
        {
            return 1;
        }

        if (str_contains($bidang,'unung'))
        {
            return 2;
        }

        if (str_contains($bidang,'anah'))
        {
            return 3;
        }

        if (str_contains($bidang,'empa'))
        {
            return 4;
        }

        if (str_contains($bidang,'alai'))
        {
            return 5;
        }

        if (str_contains($bidang,'saha'))
        {
            return 6;
        }
        
        return 5;

    }

    /**     
     *   Start no dari magma_var v1
     *   
     * 
     */  
    private function endNo($type)
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
            $end        = DB::connection('magma')
                        ->table('pga_abs')
                        ->select('id_abs')
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
    private function startNo($kode)
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
    private function temptable($kode,$no)
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
    private function posmerapi($gacode,$source)
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
     *   Fungsi explode data Merapi karena disimpan dalam satu kolom
     *   untuk pelapporan data yang lebih dari 1 pos
     * 
     *   @param string $data
     * 
     */
    private function merexplode($data)
    {

        $ex         = explode('#',$data);
        
        return $ex[0];

    }

    /**     
     *   Update Pos Pengamatan Gunung Api
     *   disiapkan untuk Gunung Api yang memiliki lebih dari 1 pos
     * 
     *   @param string $gacode, $source
     * 
     */
    private function obscode($gacode,$source)
    {
        
        if ($gacode!='MER')
        {

            $obscode        = $gacode.'1';
            return $obscode;

        }

        if ($gacode=='MER')
        {

            $obscode        = $this->posmerapi($gacode,$source);
            return $obscode;

        }

    }

    /**     
     *   Fungsi Update Pos Pengamatan Gunungapi
     * 
     *   @param string $ga_code, $code, $name
     * 
     */
    private function updatePos($ga_code,$code,$name,$tzone)
    {

        $pos = DB::connection('magma')->table('ga_dd')
            ->select(
                'ga_adr_pos',
                'ga_elev_pos',
                'ga_lat_pos',
                'ga_lon_pos'
            )
            ->where('ga_code','=',$ga_code)
            ->first();

        $updatePos = PosPga::firstOrCreate(
                [   
                    'obscode'  => $code
                ],
                [   
                    'code_id'     => $ga_code,
                    'observatory' => $name,
                    'address'     => $pos->ga_adr_pos,
                    'elevation'   => $pos->ga_elev_pos,
                    'latitude'    => $pos->ga_lat_pos,
                    'longitude'   => $pos->ga_lon_pos
                ]
            );

        $updateKantor = Kantor::firstOrCreate(
                [
                    'code' => $code
                ],
                [
                    'nama' => $name,
                    'tzone' => $tzone,
                    'address' => $pos->ga_adr_pos,
                    'elevation' => $pos->ga_elev_pos,
                    'latitude' => $pos->ga_lat_pos,
                    'longitude' => $pos->ga_lon_pos
                ]
            );

    }

    /**     
     *   Untuk intersect data array
     * 
     *   @param string $a array, $b array
     * 
     */
    private function sect($a,$b)
    {

        $inter      = array_intersect($a,$b);

        if ($inter)
        {

            $inter      = collect($inter);

            $inter      = $inter->values();

            return $inter;

        }

        return array();

    }

    /**     
     *   Fungsi Update Visibility dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $v
     * 
     */
    private function visibility($v)
    {
        $v                  = str_replace('Kabut-I','Kabut 0-I',$v);
        $v                  = str_replace('Kabut-II','Kabut 0-II',$v);
        $v                  = str_replace('Kabut-III','Kabut 0-III',$v);

        $v                  = str_replace(', ',',',$v);

        $s_visibility       = ['Jelas','Kabut 0-I','Kabut 0-II','Kabut 0-III'];

        $visibility         = explode(',',$v);

        return $this->sect($s_visibility,$visibility);

    }

    /**     
     *   Update Visual Asap dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $asap, $tinggimin, $tinggimax
     * 
     */
    private function asap($asap,$tmin,$tmax)
    {

        $asap         = str_replace(', ',',',$asap);

        $s_asap       = ['Nihil','Teramati','Tidak Teramati'];
        
        if (empty($asap))
        {

            if (($tmin>0) || ($tmax>0))
            {

                $asap   = 'Teramati';
                return $asap;

            }

            $asap = 'Tidak Teramati';
            return $asap;

        }

        $asap = explode(',',$asap);
        $asap = $this->sect($s_asap,$asap);

        return $asap[0];

    }

    /**     
     *   Update Warna Asap dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $asap, $tinggimin, $tinggimax
     * 
     */
    private function wasap($wasap)
    {

        $s_wasap        = ['-','Putih','Kelabu','Cokelat','Hitam'];

        $wasap          = str_replace(', ',',',$wasap);

        if (empty($wasap))
        {

            $wasap      = ['-'];
            return $wasap;

        }

        $wasap          = str_replace('Coklat','Cokelat',$wasap);
        $wasap          = explode(',',$wasap);

        return $this->sect($s_wasap,$wasap);

    }

    /**     
     *   Update Intensitas Asap dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $intensitas_asap
     * 
     */
    private function intasap($intasap)
    {

        $s_intasap      = ['Tipis','Sedang','Tebal'];

        $intasap        = str_replace(', ',',',$intasap);

        if (empty($intasap))
        {

            $intasap        = ['Tipis'];
            return $intasap;

        }

        $intasap        = explode(',',$intasap);

        return $this->sect($s_intasap,$intasap);

    }

    /**     
     *   Update Tekanan Asap dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $tekanan_asap
     * 
     */
    private function tekasap($tekasap)
    {

        $s_tekasap     = ['Lemah','Sedang','Kuat'];

        $tekasap       = str_replace(', ',',',$tekasap);


        if (empty($tekasap) || $tekasap == '-')
        {

            $tekasap    = ['Lemah'];
            return $tekasap;

        }

        $tekasap        = explode(',',$tekasap);
        return $this->sect($s_tekasap,$tekasap);

    }

    /**     
     *   Update Visual Kawah dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $visual_kawah
     * 
     */
    private function viskawah($viskawah)
    {

        if (strlen($viskawah)>5)
        {

            $vis        = explode('#',$viskawah);
            $data       = '';

            foreach ($vis as $v) 
            {
                
                if (strlen($v)>5)
                {

                    $data = $data.' '.$v;

                }

            }

            if (empty($data))
            {
                $data = 'Nihil';
            }

            return $data;

        }

        return 'Nihil';        

    }

    /**     
     *   Update Cuaca Kawah dari
     *   MAGMA v1 ke v2
     * 
     *   @param string $cuaca
     * 
     */
    private function cuaca($cuaca)
    {

        $s_cuaca    = ['Cerah','Berawan','Mendung','Hujan','Badai'];

        $cuaca      = str_replace(', ',',',$cuaca);

        if (empty($cuaca))
        {

            $cuaca        = 'Cerah';

        }

        $cuaca      = explode(',',$cuaca);

        return $this->sect($s_cuaca,$cuaca);

    }


    /**     
     *   Validasi nilai minus dan empty data
     *   MAGMA v1 ke v2
     * 
     *   @param string $type, $data, $gacode
     *   @var $type suhu, lembab, tekanan
     * 
     */
    private function cekminusdanempty($type,$data,$gacode)
    {

        if (empty($data) || $data=='-')
        {

            $data        = 0;

        }

        if ($gacode=='MER')
        {

            return $data;

        }

        switch ($type) {
            case 'tekanan':
                if ($data>999){
                    $data = $data/10;
                }
                break;
            default:
                if ($data>999){
                    $data = $data/100;
                }
                if ($data>99){
                    $data = $data/10;
                }
                break;
        }

        $data       = floatval($data);
        return $data;       

    }

    /**     
     *   Validasi nilai dari kecepatan angin
     *   MAGMA v1 ke v2
     * 
     *   @param string $data
     * 
     */    
    private function kecangin($data)
    {

        $s_kec      = ['Tenang','Lemah','Sedang','Kencang'];

        $data       = str_replace(', ',',',$data);

        $kec        = explode(',',$data);

        return $this->sect($s_kec,$kec);

    }

    /**     
     *   Validasi arah (asap, angin, guguran dll)
     *   MAGMA v1 ke v2
     * 
     *   @param string $data
     * 
     */    
    private function arah($data)
    {

        $s_arah      = ['Utara','Timur Laut','Timur','Tenggara','Selatan','Barat Daya','Barat','Barat Laut'];

        $data        = str_replace(', ',',',$data);

        $arah        = explode(',',$data);

        return $this->sect($s_arah,$arah);

    }

    /**     
     *   Validasi skala untuk Gempa Terasa
     *   MAGMA v1 ke v2
     * 
     *   @param string $data
     * 
     */    
    private function skala($data)
    {

        $s_skala     = ['I','II','III','IV','V','VI','VII'];

        $data        = str_replace(', ',',',$data);

        $skala       = explode(',',$data);

        return $this->sect($s_skala,$skala);

    }

    /**
     * import Penanggung jawab
     *
     * @param string $noticenumber
     * @param string $var_nip_pj
     * @return void
     */
    protected function varPj($noticenumber,$var_nip_pj)
    {
        $data = VarPj::firstOrCreate(
            [
                'noticenumber_id' => $noticenumber
            ],
            [
                'nip_id' => $var_nip_pj,
            ]
        );

        return $data;
    }

    /**
     * Import Verifikator
     *
     * @param string $noticenumber
     * @param string $var_nip_verifikator
     * @return void
     */
    protected function varVerifikator(string $noticenumber,string $var_nip_verifikator)
    {
        $data = VarVerifikator::firstOrCreate(
            [
                'noticenumber_id' => $noticenumber
            ],
            [
                'nip_id' => $var_nip_verifikator,
            ]
        );

        return $data;
    }
}