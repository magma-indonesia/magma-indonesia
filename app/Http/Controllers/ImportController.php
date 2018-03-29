<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\MagmaHelper;
use App\Traits\JenisGempa;
use App\User;
use App\UserBidang;
use App\Gadd;
use App\PosPga;
use App\MagmaVar;
use App\VarDaily;
use App\VarVisual;
use App\VarAsap;
use App\VarKlimatologi;
use App\VarPj;
use App\VarVerifikator;
use App\VarGempa;
use App\Status;

class ImportController extends Controller
{

    use MagmaHelper,JenisGempa;

    public function __construct(Request $request)
    {

        ini_set('max_execution_time', 1200);

    }

    /**     
     *   Untuk Import Data dasar Gunung Api
     *   dari Magma v1 ke Magma v2
     * 
     */    
    public function gadds()
    {
        $gadds  = DB::connection('magma')
                ->table('ga_dd')
                ->orderBy('no')
                ->get();
        
        $gadds  = $gadds->map(function ($item) {

            Gadd::firstOrCreate(
                [   'code'              => $item->ga_code],
                [   'name'              => $item->ga_nama_gapi,
                    'alias'             => $item->ga_alias_gapi,
                    'tzone'             => $item->ga_tzone,
                    'zonearea'          => $item->ga_zonearea,
                    'district'          => $item->ga_kab_gapi,
                    'province'          => $item->ga_prov_gapi,
                    'nearest_city'      => $item->ga_koter_gapi,
                    'division'          => $item->ga_wil_gapi,
                    'volc_type'         => $item->ga_tipe_gapi,
                    'elevation'         => $item->ga_elev_gapi,
                    'latitude'          => $item->ga_lat_gapi,
                    'longitude'         => $item->ga_lon_gapi,
                    'smithsonian_id'    => $item->ga_id_smithsonian
                ]
            );

            return [

                'code'              => $item->ga_code,
                'name'              => $item->ga_nama_gapi,

            ];

        });

        $gadds = Gadd::select('code','name')->get();
        $gadds = $gadds->map(function ($item) {

            $gacode         = $item->code;
            $name           = $item->name;

            if ($gacode!='MER')
            {

                $obscode    = $gacode.'1';
                $var_source = 'Pos Pengamatan Gunungapi '.$name;

                $update     = $this->updatePos($gacode,$obscode,$var_source);

            } else {

                $obs       = [

                                '1' => 'Pos Pengamatan Gunung Merapi - Jarakah',
                                '2' => 'Pos Pengamatan Gunung Merapi - Babadan',
                                '3' => 'Pos Pengamatan Gunung Merapi - Kaliurang',
                                '4' => 'Pos Pengamatan Gunung Merapi - Ngepos',
                                '5' => 'Pos Pengamatan Gunung Merapi - Selo'

                            ];

                foreach ($obs as $key => $value)
                {

                    $obscode    = $gacode.$key;
                    $var_source = $value;

                    $update     = $this->updatePos($gacode,$obscode,$var_source);

                }

            }

            return [

                'code'              => $item->code,
                'name'              => $item->name,

            ];

        });

        if ($gadds){

            $data = [
                'success' => 1,
                'message' => 'Data Dasar Gunung Api berhasil diperbarui',
                'count' => Gadd::count()
            ];

            $this->sendNotif('Data Dasar Gunung Api');
    
            return response()->json($data);
        }
    }

    public function users()
    {
        $users  = DB::connection('magma')
                ->table('vg_peg')
                ->select(
                    'vg_nama AS name',
                    'vg_nip AS nip',
                    'vg_email AS email',
                    'vg_hp AS phone',
                    'vg_password AS password'
                    )
                ->orderBy('id')
                ->get();

        $users = $users->map(function ($item) {

            strlen($item->email)>5 ? $email = $item->email : $email = NULL;
            strlen($item->phone)>9 ? $phone = str_replace('+62','0',$item->phone) : $phone = NULL;
            
            User::updateOrCreate(
                    [ 'nip' => $item->nip ],
                    [                 
                        'name'  => $item->name,
                        'email' => $email,
                        'phone' => $phone,
                        'password' => $item->password,
                        'status' => 1
                    ]
                );

            return [

                'name'  => $item->name,

            ];
        });

        if ($users){

            $data = [
                'success' => 1,
                'message' => 'Data Users berhasil diperbarui',
                'count' => User::count()
            ];
    
            $this->sendNotif('Users');
            
            return response()->json($data);
        }

    }

    public function bidang()
    {
        $users  = DB::connection('magma')
                ->table('vg_peg')
                ->select(
                    'vg_nip AS nip',
                    'vg_bid AS bidang'
                    )
                ->orderBy('id')
                ->get();

        foreach ($users as $user) {

            $bidang = $this->bidangConvert($user->bidang);
            $user = User::where('nip',$user->nip)->firstOrFail();
            // return $user;

            $update             = UserBidang::firstOrCreate(
                                        ['user_id' => $user->id], ['user_bidang_desc_id'  => $bidang]   
                                    );

        }

    }

    public function vars()
    {

        $vars           = DB::connection('magma')
                        ->table('magma_var')
                        ->select(

                            'no',
                            'ga_code',
                            'var_noticenumber',
                            'cu_status',
                            'var_issued',
                            'var_data_date',
                            'periode',
                            'var_perwkt',
                            'var_source',
                            'var_nip_pelapor',
                            'var_nip_pemeriksa_pj',
                            'var_nip_pemeriksa',
                            'var_log'

                        )
                        ->whereBetween('no',[$this->startNo('vars'),$this->endNo()])
                        ->orderBy('no')
                        ->chunk(1000, function($var)
                        {

                            foreach ($var as $varx) 
                            {
                                $no             = $varx->no;

                                $gacode                     = $varx->ga_code;
                                $var_issued                 = str_replace('/','-',$varx->var_issued);
                                $var_issued                 = date('Y-m-d H:i:s', strtotime($var_issued));
                                $var_nip_pelapor            = $varx->var_nip_pelapor;
                                $var_nip_pj                 = $varx->var_nip_pemeriksa_pj;
                                $var_nip_verifikator        = $varx->var_nip_pemeriksa;
                                $var_source                 = $varx->var_source;

                                if (empty($var_nip_pelapor))
                                {
                                    $var_nip_pelapor        = '198803152015031005';
                                }

                                if ($var_nip_pelapor == '3273182505850001')
                                {
                                    $var_nip_pelapor        = '3273182505850007';
                                }

                                if ($var_nip_pelapor == '196807071992051001' || $var_nip_pelapor == '196807071992031001')
                                {
                                    $var_nip_pelapor        = '196807071992031018';
                                }

                                $obscode                    = $this->obscode($gacode,$var_source);

                                $noticenumber               = $obscode.$varx->var_noticenumber;

                                $magmavar                   = MagmaVar::firstOrCreate(

                                                                [
                                                                    'noticenumber'          => $noticenumber
                                                                ],

                                                                [
                                                                    'var_issued'            => $var_issued,
                                                                    'code_id'               => $varx->ga_code,
                                                                    'var_data_date'         => $varx->var_data_date,
                                                                    'periode'               => $varx->periode,
                                                                    'var_perwkt'            => intval($varx->var_perwkt),
                                                                    'obscode_id'            => $obscode,
                                                                    'statuses_desc_id'      => $this->getStatus($varx->cu_status),
                                                                    'nip_pelapor'           => $var_nip_pelapor,
                                                                    'created_at'            => $varx->var_log
                                                                ]

                                                            );

                                if ($magmavar)
                                {

                                    if (!empty($varx->var_nip_pemeriksa_pj))
                                    {
                                        $pj = VarPj::firstOrCreate(
                                            [
                                                'noticenumber_id' => $noticenumber
                                            ],
                                            [
                                                'nip_id' => $var_nip_pj,
                                            ]
                                        );
                                    }
    
                                    if (!empty($varx->var_nip_pemeriksa))
                                    {
                                        $verifikator = VarVerifikator::firstOrCreate(
                                            [
                                                'noticenumber_id' => $noticenumber
                                            ],
                                            [
                                                'nip_id' => $var_nip_verifikator,
                                            ]
                                        );
                                    }

                                    $this->temptable('vars',$no);
                                }
                            }

                        });

        $data = [
            'success' => 1,
            'message' => 'Data Vars berhasil diperbarui',
            'count' => MagmaVar::count()
        ];

        $this->sendNotif('Data MAGMA-VAR v1');

        return response()->json($data);
    }

    public function dailies()
    {
        $pospga             = PosPga::select('code_id','obscode')
                            ->whereNotIn('code_id',['TEO','SBG'])
                            ->get();

        foreach ($pospga as $gadd) {
        
            $code           = $gadd->code_id;
            $obscode        = $gadd->obscode;

            $noticenumber   = MagmaVar::select('noticenumber')
                            ->where('noticenumber','like','%'.$obscode.'%')
                            ->orderBy('var_data_date','desc')
                            ->first();

            $noticenumber   = $noticenumber['noticenumber'];

            if (!empty($noticenumber))
            {

                $update         = VarDaily::updateOrCreate(

                                    ['code_id'  => $code],
                                    ['noticenumber_id' => $noticenumber]

                                );

            }
            
        }

        $data = [
            'success' => 1,
            'message' => 'Var Daily berhasil diperbarui',
            'count' => VarDaily::count()
        ];

        $this->sendNotif('Laporan Harian');

        return response()->json($data);
    }

    public function visuals()
    {

        $vars           = DB::connection('magma')
                        ->table('magma_var')
                        ->select(

                            'no',
                            'ga_code',
                            'var_image',
                            'var_image_create',
                            'var_issued',
                            'var_source',
                            'var_noticenumber',
                            'var_visibility',
                            'var_asap',
                            'var_tasap_min',
                            'var_tasap',
                            'var_wasap',
                            'var_intasap',
                            'var_tekasap',
                            'var_viskawah'

                        )
                        ->whereBetween('no',[$this->startNo('visuals'),$this->endNo()])
                        ->orderBy('no','asc')
                        ->chunk(1000,function($varx)
                        {
                            foreach ($varx as $vars) 
                            {

                                $no             = $vars->no;
                                $gacode         = $vars->ga_code;
                                $issued         = $vars->var_issued;
                                $source         = $vars->var_source;
                                $image          = $vars->var_image;
                                $imagecreated   = $vars->var_image_create;
                                $obscode        = $gacode.'1';
                                $visibility     = $this->visibility($vars->var_visibility);

                                $visual_asap    = $vars->var_asap;
                                $tasapmin       = $vars->var_tasap_min;
                                $tasapmax       = $vars->var_tasap;
                                if (empty($tasapmin)){

                                    $tasapmin   = 0.0;

                                }

                                if (empty($tasapmax))
                                {

                                    $tasapmax   = 0.0;

                                }
                                $visual_asap    = $this->asap($visual_asap,$tasapmin,$tasapmax);
                            
                                $visual_kawah   = $this->viskawah($vars->var_viskawah);                                

                                $wasap          = $this->wasap($vars->var_wasap);
                                $intasap        = $this->intasap($vars->var_intasap);
                                $tekasap        = $this->tekasap($vars->var_tekasap);

                                if ($gacode=='MER')
                                {

                                    $image          = $this->merexplode($vars->var_image);
                                    $imagecreated   = $this->merexplode($vars->var_image_create);
                                    $obscode        = $this->posmerapi($gacode,$source);
                                    $source         = $source;
                                    $obscode        = $this->posmerapi($gacode,$source);

                                    $visibility     = $this->visibility(str_replace('#',', ',$vars->var_visibility));

                                    $tasapmax       = max(array_map('intval', explode('#',$vars->var_tasap)));
                                    $visual_asap    = $this->asap($vars->var_asap,$tasapmin,$tasapmax);

                                    $wasap          = $this->wasap(str_replace('#',', ',$vars->var_wasap));
                                    $intasap        = $this->intasap(str_replace('#',', ',$vars->var_intasap));
                                    $tekasap        = $this->tekasap(str_replace('#',', ',$vars->var_tekasap));
                                    $visual_kawah   = $this->viskawah($vars->var_viskawah);

                                }

                                $noticenumber   = $obscode.$vars->var_noticenumber;

                                $update             = VarVisual::firstOrCreate(

                                                        [   'noticenumber_id'   => $noticenumber],
                                                        [   'visibility'        => $visibility,
                                                            'visual_asap'       => $visual_asap,
                                                            'visual_kawah'      => $visual_kawah
                                                        ]   

                                                    );
                                if ($update) 
                                {
                                    $this->temptable('visuals',$no);
                                }

                                if ($visual_asap=='Teramati'){
                                    
                                    $visual_id          = VarVisual::select('id')->where('noticenumber_id',$noticenumber)->first()->id;
                                    $update             = VarAsap::firstOrCreate(

                                                            [   'var_visual_id'     => $visual_id],
                                                            [   'tasap_min'         => $tasapmin,
                                                                'tasap_max'         => $tasapmax,
                                                                'wasap'             => $wasap,
                                                                'intasap'           => $intasap,
                                                                'tekasap'           => $tekasap
                                                            ]   

                                                        );
                                }                                                    

                                
                            }
                        });

        $data = [
            'success' => 1,
            'message' => 'Var Visual berhasil diperbarui',
            'count' => VarVisual::count()
        ];

        $this->sendNotif('Visual');

        return response()->json($data);                            

    }

    public function klimatologis()
    {
        $vars           = DB::connection('magma')
                        ->table('magma_var')
                        ->select(

                            'no',
                            'ga_code',
                            'var_noticenumber',
                            'var_source',
                            'var_cuaca',
                            'var_curah_hujan',
                            'var_suhumin',
                            'var_suhumax',
                            'var_kelembabanmin',
                            'var_kelembabanmax',
                            'var_tekananmin',
                            'var_tekananmax',
                            'var_kecangin',
                            'var_arangin'

                        )
                        ->whereBetween('no',[$this->startNo('klima'),$this->endNo()])
                        ->orderBy('no','asc')
                        ->chunk(1000,function($varx){

                            foreach ($varx as $var) {

                                $no             = $var->no;
                                $gacode         = $var->ga_code;

                                if ($gacode!='MER')
                                {
                                    $obscode        = $gacode.'1';
                                    $noticenumber   = $obscode.$var->var_noticenumber;
                                    $cuaca          = $this->cuaca($var->var_cuaca);
                                    $suhumin        = $this->cekminusdanempty('suhu',$var->var_suhumin,$gacode);
                                    $suhumax        = $this->cekminusdanempty('suhu',$var->var_suhumax,$gacode);
                                    $lembabmin      = $this->cekminusdanempty('lembab',$var->var_kelembabanmin,$gacode);
                                    $lembabmax      = $this->cekminusdanempty('lembab',$var->var_kelembabanmax,$gacode);
                                    $tekmin         = $this->cekminusdanempty('tekanan',$var->var_tekananmin,$gacode);
                                    $tekmax         = $this->cekminusdanempty('tekanan',$var->var_tekananmax,$gacode);
                                    $kecangin       = $this->kecangin($var->var_kecangin);
                                    $arangin        = $this->arah($var->var_arangin);
                                }

                                if ($gacode=='MER')
                                {

                                    $source         = $var->var_source;
                                    $obscode        = $this->posmerapi($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;
                                    $cuaca          = $this->cuaca(str_replace('#',', ',$var->var_cuaca));

                                    $suhumin        = max(array_map('intval', explode('#',$this->cekminusdanempty('suhu',$var->var_suhumin,$gacode))));
                                    $suhumax        = max(array_map('intval', explode('#',$this->cekminusdanempty('suhu',$var->var_suhumax,$gacode))));

                                    $lembabmin      = max(array_map('intval', explode('#',$this->cekminusdanempty('lembab',$var->var_kelembabanmin,$gacode))));
                                    $lembabmax      = max(array_map('intval', explode('#',$this->cekminusdanempty('lembab',$var->var_kelembabanmax,$gacode))));

                                    $tekmin         = max(array_map('intval', explode('#',$this->cekminusdanempty('tekanan',$var->var_tekananmin,$gacode))));
                                    $tekmax         = max(array_map('intval', explode('#',$this->cekminusdanempty('tekanan',$var->var_tekananmax,$gacode))));

                                    $kecangin       = $this->kecangin(str_replace('#',', ',$var->var_kecangin));
                                    $arangin        = $this->arah(str_replace('#',', ',$var->var_arangin));
                                    
                                }

                                $update             = VarKlimatologi::firstOrCreate(

                                                        [   'noticenumber_id'   => $noticenumber],
                                                        [   'cuaca'             => $cuaca,
                                                            'kecangin'          => $kecangin,
                                                            'arahangin'         => $arangin,
                                                            'suhumin'           => $suhumin,
                                                            'suhumax'           => $suhumax,
                                                            'lembabmin'         => $lembabmin,
                                                            'lembabmax'         => $lembabmax,
                                                            'tekmin'            => $tekmin,
                                                            'tekmax'            => $tekmax
                                                        ]   

                                                    );

                                if ($update)
                                {
                                    $this->temptable('klima',$no);
                                }

                            };

                        });

        $data = [
            'success' => 1,
            'message' => 'Data Klimatologi berhasil diperbarui',
            'count' => VarKlimatologi::count()
        ];

        $this->sendNotif('Data Klimatologi');

        return response()->json($data);      
    }

    public function gempa()
    {

        $jenis      = $this->jenisgempa();

        foreach ($jenis as $key => $x) 
        {

           $nama    = $x->nama;
           $jenis   = $x->jenis;
           $kode    = $x->kode;
           $select  = $x->select;

           if ($jenis=='sp')
           {

                $gempa      = new \App\GempaSP;
                $table      = 'e_'.$kode;
                $gempa->setTable($table);

                $vars       = DB::connection('magma')
                            ->table('magma_var')
                            ->select($select)
                            ->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                            ->where('var_'.$kode,'>',0)
                            ->orderBy('no', 'asc')
                            ->chunk(1000,function($varx) use ($kode,$gempa)
                            {

                                foreach ($varx as $var) 
                                {
                                
                                    $no             = $var->no;
                                    $gacode         = $var->ga_code;
                                    $source         = $var->var_source;

                                    $obscode        = $this->obscode($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;

                                    $jumlah     = $var->{'var_'.$kode};
                                    $amin       = $var->{'var_'.$kode.'_amin'};
                                    $amax       = $var->{'var_'.$kode.'_amax'};
                                    $spmin      = $var->{'var_'.$kode.'_spmin'};
                                    $spmax      = $var->{'var_'.$kode.'_spmax'};
                                    $dmin       = $var->{'var_'.$kode.'_dmin'};
                                    $dmax       = $var->{'var_'.$kode.'_dmax'};

                                    $addGempa   = VarGempa::firstOrCreate(['noticenumber_id' => $noticenumber]);

                                    $update     = $gempa->firstOrCreate(

                                        [   'var_gempa_id'   => $addGempa->id],
                                        [

                                            'noticenumber_id'   => $noticenumber,
                                            'jumlah'            => $jumlah,
                                            'amin'              => $amin,
                                            'amax'              => $amax,
                                            'spmin'             => $spmin,
                                            'spmax'             => $spmax,
                                            'dmin'              => $dmin,
                                            'dmax'              => $dmax

                                        ]       

                                    );
                                    
                                    if ($update)
                                    {
                                        $this->temptable($kode,$no);
                                    }

                                }

                            });

                //$this->sendNotif(($key+1).'. Gempa '.$nama);

           }

           if ($jenis=='normal')
           {

                $gempa      = new \App\GempaNormal;
                $table      = 'e_'.$kode;
                $gempa->setTable($table);

                $vars       = DB::connection('magma')
                            ->table('magma_var')
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                            ->where('var_'.$kode,'>',0)
                            ->orderBy('no', 'asc')
                            ->chunk(1000,function($varx) use ($kode,$gempa)
                            {

                                foreach ($varx as $var) 
                                {
                                
                                    $no             = $var->no;
                                    $gacode         = $var->ga_code;
                                    $source         = $var->var_source;

                                    $obscode        = $this->obscode($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;

                                    $jumlah     = $var->{'var_'.$kode};
                                    $amin       = $var->{'var_'.$kode.'_amin'};
                                    $amax       = $var->{'var_'.$kode.'_amax'};
                                    $dmin       = $var->{'var_'.$kode.'_dmin'};
                                    $dmax       = $var->{'var_'.$kode.'_dmax'};

                                    $addGempa   = VarGempa::firstOrCreate(['noticenumber_id' => $noticenumber]);                                    

                                    $update     = $gempa->firstOrCreate(

                                        [   'var_gempa_id'   => $addGempa->id],
                                        [

                                            'noticenumber_id'   => $noticenumber,
                                            'jumlah'            => $jumlah,
                                            'amin'              => $amin,
                                            'amax'              => $amax,
                                            'dmin'              => $dmin,
                                            'dmax'              => $dmax

                                        ]       

                                    );
                                    if ($update)
                                    {
                                        $this->temptable($kode,$no);
                                    }

                                }

                            });

                //$this->sendNotif(($key+1).'. Gempa '.$nama);

           }

           if ($jenis=='dominan')
           {

                $gempa      = new \App\GempaDominan;
                $table      = 'e_'.$kode;
                $gempa->setTable($table);

                $vars       = DB::connection('magma')
                            ->table('magma_var')
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                            ->where('var_'.$kode,'>',0)
                            ->orderBy('no', 'asc')
                            ->chunk(1000,function($varx) use ($kode,$gempa)
                            {
    
                                foreach ($varx as $var) 
                                {
                                
                                    $no             = $var->no;
                                    $gacode         = $var->ga_code;
                                    $source         = $var->var_source;
    
                                    $obscode        = $this->obscode($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;
      
                                    $jumlah     = $var->{'var_'.$kode};
                                    $amin       = $var->{'var_'.$kode.'_amin'};
                                    $amax       = $var->{'var_'.$kode.'_amax'};
                                    $adom       = $var->{'var_'.$kode.'_adom'};

                                    $addGempa   = VarGempa::firstOrCreate(['noticenumber_id' => $noticenumber]);                                    
    
                                    $update     = $gempa->firstOrCreate(
    
                                        [   'var_gempa_id'   => $addGempa->id],
                                        [
    
                                            'noticenumber_id'   => $noticenumber,
                                            'jumlah'            => $jumlah,
                                            'amin'              => $amin,
                                            'amax'              => $amax,
                                            'adom'              => $adom
    
                                        ]       
    
                                    );

                                    if ($update)
                                    {
                                        $this->temptable($kode,$no);
                                    }
    
                                }
    
                            });
                
                //$this->sendNotif(($key+1).'. Gempa '.$nama);  
                
           }

           if ($jenis=='luncuran')
           {

                $gempa      = new \App\GempaLuncuran;
                $table      = 'e_'.$kode;
                $gempa->setTable($table);

                $vars       = DB::connection('magma')
                            ->table('magma_var')
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                            ->where('var_'.$kode,'>',0)
                            ->orderBy('no', 'asc')
                            ->chunk(1000,function($varx) use ($kode,$gempa)
                            {

                                foreach ($varx as $var) 
                                {
                                
                                    $no             = $var->no;
                                    $gacode         = $var->ga_code;
                                    $source         = $var->var_source;

                                    $obscode        = $this->obscode($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;

                                    $jumlah     = $var->{'var_'.$kode};
                                    $amin       = $var->{'var_'.$kode.'_amin'};
                                    $amax       = $var->{'var_'.$kode.'_amax'};
                                    $dmin       = $var->{'var_'.$kode.'_dmin'};
                                    $dmax       = $var->{'var_'.$kode.'_dmax'};
                                    $rmin       = $var->{'var_'.$kode.'_rmin'};
                                    $rmax       = $var->{'var_'.$kode.'_rmax'};
                                    $arah       = $var->{'var_'.$kode.'_alun'};
                                    $arah       = $this->arah($arah);

                                    if (empty($arah))
                                    {

                                        $arah       = null;

                                    }

                                    $addGempa   = VarGempa::firstOrCreate(['noticenumber_id' => $noticenumber]);                                    

                                    $update     = $gempa->firstOrCreate(

                                        [   'var_gempa_id'   => $addGempa->id],
                                        [

                                            'noticenumber_id'   => $noticenumber,
                                            'jumlah'            => $jumlah,
                                            'amin'              => $amin,
                                            'amax'              => $amax,
                                            'dmin'              => $dmin,
                                            'dmax'              => $dmax,
                                            'rmin'              => $rmin,
                                            'rmax'              => $rmax,
                                            'arah'              => $arah

                                        ]       

                                    );

                                    if ($update)
                                    {
                                        $this->temptable($kode,$no);
                                    }

                                }

                            });

                //$this->sendNotif(($key+1).'. Gempa '.$nama);

           }

           if ($jenis=='erupsi')
           {

                $gempa      = new \App\GempaErupsi;
                $table      = 'e_'.$kode;
                $gempa->setTable($table);
            
                $vars       = DB::connection('magma')
                            ->table('magma_var')
                            ->select($select)
                            ->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                            ->where('var_'.$kode,'>',0)
                            ->orderBy('no', 'asc')
                            ->chunk(1000,function($varx) use ($kode,$gempa)
                            {

                                foreach ($varx as $var) 
                                {
                                
                                    $no             = $var->no;
                                    $gacode         = $var->ga_code;
                                    $source         = $var->var_source;

                                    $obscode        = $this->obscode($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;

                                    $jumlah     = $var->{'var_'.$kode};
                                    $amin       = $var->{'var_'.$kode.'_amin'};
                                    $amax       = $var->{'var_'.$kode.'_amax'};
                                    $dmin       = $var->{'var_'.$kode.'_dmin'};
                                    $dmax       = $var->{'var_'.$kode.'_dmax'};
                                    $tmin       = $var->{'var_'.$kode.'_tmin'};
                                    $tmax       = $var->{'var_'.$kode.'_tmax'};

                                    $addGempa   = VarGempa::firstOrCreate(['noticenumber_id' => $noticenumber]);    

                                    $update     = $gempa->firstOrCreate(

                                        [   'var_gempa_id'   => $addGempa->id],
                                        [

                                            'noticenumber_id'   => $noticenumber,
                                            'jumlah'            => $jumlah,
                                            'amin'              => $amin,
                                            'amax'              => $amax,
                                            'dmin'              => $dmin,
                                            'dmax'              => $dmax,
                                            'tmin'              => $tmin,
                                            'tmax'              => $tmax

                                        ]       

                                    );

                                    if ($update)
                                    {
                                        $this->temptable($kode,$no);
                                    }
                                }

                            });

                //$this->sendNotif(($key+1).'. Gempa '.$nama);

           }

           if ($jenis=='terasa')
           {

                $gempa      = new \App\GempaTerasa;
                $table      = 'e_'.$kode;
                $gempa->setTable($table);

                $vars       = DB::connection('magma')
                            ->table('magma_var')
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                            ->where('var_'.$kode,'>',0)
                            ->orderBy('no', 'asc')
                            ->chunk(1000,function($varx) use ($kode,$gempa)
                            {

                                foreach ($varx as $var) 
                                {
                                
                                    $no             = $var->no;
                                    $gacode         = $var->ga_code;
                                    $source         = $var->var_source;

                                    $obscode        = $this->obscode($gacode,$source);
                                    $noticenumber   = $obscode.$var->var_noticenumber;

                                    $jumlah     = $var->{'var_'.$kode};
                                    $amin       = $var->{'var_'.$kode.'_amin'};
                                    $amax       = $var->{'var_'.$kode.'_amax'};
                                    $spmin      = $var->{'var_'.$kode.'_spmin'};
                                    $spmax      = $var->{'var_'.$kode.'_spmax'};
                                    $dmin       = $var->{'var_'.$kode.'_dmin'};
                                    $dmax       = $var->{'var_'.$kode.'_dmax'};
                                    $skala      = $var->{'var_'.$kode.'_skalamin'}.','.$var->{'var_'.$kode.'_skalamax'};
                                    $skala      = $this->skala($skala);

                                    $addGempa   = VarGempa::firstOrCreate(['noticenumber_id' => $noticenumber]);
                                    
                                    $update     = $gempa->firstOrCreate(

                                        [   'var_gempa_id'   => $addGempa->id],
                                        [

                                            'noticenumber_id'   => $noticenumber,
                                            'jumlah'            => $jumlah,
                                            'amin'              => $amin,
                                            'amax'              => $amax,
                                            'spmin'             => $spmin,
                                            'spmax'             => $spmax,
                                            'dmin'              => $dmin,
                                            'dmax'              => $dmax,
                                            'skala'             => $skala

                                        ]       

                                    );

                                    if ($update)
                                    {
                                        $this->temptable($kode,$no);
                                    }

                                }

                            });

                //$this->sendNotif(($key+1).'. Gempa '.$nama);

           }
        
        }

        $this->sendNotif('Data Gempa');

        $data = [
            'success' => 1,
            'message' => 'Data Kegempaan berhasil diperbarui',
            'count' => VarGempa::jumlah()
        ];

        return response()->json($data);
 
    }

    public function status()
    {
        $gadds  = Gadd::select('code')->get();

        $statuses = ['Level I (Normal)','Level II (Waspada)','Level III (Siaga)','Level IV (Awas)'];

        foreach ($gadds as $gadd)
        {
            $kode = 'status-'.$gadd->code;

            $vars = DB::connection('magma')
                    ->table('magma_var')
                    ->select('no','ga_code','cu_status')
                    ->where('ga_code',$gadd->code)
                    ->whereBetween('no',[$this->startNo($kode),$this->endNo()])
                    ->chunk(1000, function($var){

                        foreach ($var as $key => $varx)
                        {
                
                            $no = $varx->no;

                            if ($key == 0)
                            {
                                $status = $varx->cu_status;

                            }

                            if ($update)
                            {
                                $this->temptable($kode,$no);
                            }


                        }

                    });

        }
    }


    public function index()
    {

        $users = User::count();
        $gadds = Gadd::count();
        $varsv1 = DB::connection('magma')->table('magma_var')->count();
        $vars = MagmaVar::count();
        $vardailies = VarDaily::count();
        $visuals = VarVisual::count();
        $klimatologis = VarKlimatologi::count();
        $gempa = new VarGempa();
        $gempa = $gempa->jumlah();
        
        return view('import.index',compact('users','gadds','varsv1','vars','vardailies','visuals','klimatologis','gempa'));
    }
}
