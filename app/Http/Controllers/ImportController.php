<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Validate;
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
use App\SigertanCrs;
use App\SigertanCrsDevices;
use App\SigertanCrsValidasi;
use App\Vona;
use App\VonaSubscriber;
use App\MagmaVen;
use App\VarLetusan;
use App\MagmaRoq;
use App\RoqTanggapan;
use App\Absensi;
use App\Kantor;

use App\v1\GertanCrs as OldCrs;
use App\v1\MagmaSigertan as OldSigertan;
use App\v1\Vona as OldVona;
use App\v1\MagmaVar as OldVar;
use App\v1\VonaSubscriber as OldSub;
use App\v1\MagmaVen as OldVen;
use App\v1\MagmaRoq as OldRoq;
use App\v1\Absensi as OldAbsensi;

use Indonesia;

class ImportController extends Controller
{

    use MagmaHelper,JenisGempa;

    public function __construct(Request $request)
    {

        ini_set('max_execution_time', 1200);

    }

    /**
     * Import data Absensi MAGMA v1
     *
     * @return void
     */
    public function absensi()
    {
        $absensis = OldAbsensi::whereBetween('id_abs',[$this->startNo('abs'),$this->endNo('abs')])->get();
        
        $absensis->each(function ($item,$key) {

            $no = $item->id_abs;
            $nip = $item->vg_nip;
            $kantor = $item->obscode;
            $code = $item->ga_code;
            $checkin = $item->checkin_time == '00:00:00' ?  null : $item->date_abs.' '.$item->checkin_time;
            $checkin_image = empty($item->checkin_image) ?  null : $item->checkin_image;
            $checkin_latitude = $item->checkin_lat;
            $checkin_longitude = $item->checkin_lon;
            $checkout = $item->checkout_time == '00:00:00' ? null : $item->date_abs.' '.$item->checkout_time;
            $checkout_image = empty($item->checkout_image) ? null  : $item->checkout_image;
            $checkout_latitude = $item->checkout_lat;
            $checkout_longitude = $item->checkout_lon;
            $distance = $item->checkin_dist;
            $duration = $item->length_work;
            $nip_ver = empty($item->nip_ver) ?  null : $item->nip_ver;
            $keterangan = $item->ket_abs;

            if ($item->date_abs != '0000-00-00') 
            {
                $create = Absensi::firstOrCreate(
                    [
                        'nip_id' => $nip,
                        'checkin' => $checkin
                    ],
                    [
                        'kantor_id' => $kantor,
                        'checkin_image' => $checkin_image,
                        'checkin_latitude' => $checkin_latitude,
                        'checkin_longitude' => $checkin_longitude,
                        'checkout' => $checkout,
                        'checkout_image' => $checkout_image,
                        'checkout_latitude' => $checkout_latitude,
                        'checkout_longitude' => $checkout_longitude,
                        'distance' => $distance,
                        'duration' => $duration,
                        'nip_verifikator' => $nip_ver,
                        'keterangan' => $keterangan
                    ]
                );
    
                if ($create)
                {
                    $this->temptable('abs',$no);
                }
            }

        }); 

        $data = [
            'success' => 1,
            'message' => 'Data Absensi berhasil diperbarui',
            'count' => Absensi::count()
        ];

        $this->sendNotif('Absensi Pegawai');

        return response()->json($data);
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
        
        $gadds->each(function ($item, $key) {

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

        });

        $gadds = Gadd::select('code','name','zonearea')->get();
        $gadds->each(function ($item, $key) {

            $gacode = $item->code;
            $name = $item->name;
            $tzone = $item->zonearea;

            if ($gacode!='MER')
            {
                $obscode = $gacode.'1';
                $var_source = 'Pos Pengamatan Gunungapi '.$name;
                $update = $this->updatePos($gacode,$obscode,$var_source,$tzone);
            } else {
                $obs = [
                    '1' => 'Pos Pengamatan Gunung Merapi - Jarakah',
                    '2' => 'Pos Pengamatan Gunung Merapi - Babadan',
                    '3' => 'Pos Pengamatan Gunung Merapi - Kaliurang',
                    '4' => 'Pos Pengamatan Gunung Merapi - Ngepos',
                    '5' => 'Pos Pengamatan Gunung Merapi - Selo'
                ];
                foreach ($obs as $key => $value)
                {
                    $obscode = $gacode.$key;
                    $var_source = $value;
                    $update = $this->updatePos($gacode,$obscode,$var_source,$tzone);
                }

            }

        });

        $updatePvg = Kantor::firstOrCreate(
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

        $updatePvg = Kantor::firstOrCreate(
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

        $data = [
            'success' => 1,
            'message' => 'Data Dasar Gunung Api berhasil diperbarui',
            'count' => Gadd::count()
        ];

        $this->sendNotif('Data Dasar Gunung Api');

        return response()->json($data);
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

        $users = $users->each(function ($item, $key) {

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

        $data = [
            'success' => 1,
            'message' => 'Data Bidang berhasil diperbarui',
            'count' => UserBidang::count()
        ];

        $this->sendNotif('Data Bidang');

        return response()->json($data);

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
                        ->whereBetween('no',[$this->startNo('vars'),$this->endNo('var')])
                        ->orderBy('no')
                        ->chunk(2000, function($var)
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
                        ->whereBetween('no',[$this->startNo('visuals'),$this->endNo('var')])
                        ->orderBy('no','asc')
                        ->chunk(2000,function($varx)
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
                        ->whereBetween('no',[$this->startNo('klima'),$this->endNo('var')])
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
                            ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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
                            ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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
                                    $wasap      = $var->{'var_'.$kode.'_wasap'};
                                    $wasap      = $this->wasap($wasap);

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

                                        ]       

                                    );

                                    $visual_id  = VarVisual::select('id')->where('noticenumber_id',$noticenumber)->first()->id;
                                    $addLetusan = VarLetusan::firstOrCreate(
                                                        [   'var_visual_id'     => $visual_id],
                                                        [   'tmin'         => $tmin,
                                                            'tmax'         => $tmax,
                                                            'wasap'        => $wasap,
                                                        ]
                                                    );

                                    if ($update AND $addLetusan)
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
                            ->select($select)->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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

    //belum beres
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
                    ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
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

    public function vona()
    {

        $olds = OldVona::whereBetween('no',[$this->startNo('vona'),$this->endNo('vona')])->get();

        $olds->each(function ($item, $key) 
        {
            $no = $item->no;
            $noticenumber = $item->notice_number;
            $issued = $item->issued;
            $type = $item->type;
            $code_id = $item->ga_code;
            $cu_code = $item->cu_avcode;
            $prev_code = $item->pre_avcode;
            $location = $item->volcano_location;
            $vas = $item->volcanic_act_summ;
            $vch_summit = $item->vc_height > 0 ? $item->vc_height - $item->summit_elevation : 0;
            $vch_asl = $item->vc_height;
            $vch_other = $item->other_vc_info;
            $remarks = strlen($item->remarks)<6 ? null : $item->remarks;
            $sent = $item->sent;
            $pelapor = empty($item->nip) ? '198803152015031005' : $item->nip;
            $pelapor = $pelapor == '196807071992051001' || $pelapor == '196807071992031001' ? '196807071992031018' : $pelapor;

            $create = Vona::firstOrCreate(
                [
                    'noticenumber' => $noticenumber,
                ],
                [
                    'issued' => $issued,
                    'type' => $type,
                    'code_id' => $code_id,
                    'cu_code' => $cu_code,
                    'prev_code' => $prev_code,                    
                    'location' => $location,
                    'vas' => $vas,
                    'vch_summit' => $vch_summit,
                    'vch_asl' => $vch_asl,
                    'vch_other' => $vch_other,
                    'remarks' => $remarks,
                    'sent' => $sent,
                    'nip_pelapor' => $pelapor
                ]
            );

            if ($create)
            {
                $this->temptable('vona',$no);
            }
        });

        $this->sendNotif('Data VONA');

        $data = [
            'success' => 1,
            'message' => 'Data VONA berhasil diperbarui',
            'count' => Vona::count()
        ];

        return response()->json($data);
    }

    public function subscribers()
    {
        $subs = OldSub::whereBetween('no',[$this->startNo('subs'),$this->endNo('subs')])->get();

        $subs->each(function ($item, $Key)
        {
            $no = $item->no;
            $name = $item->nama;
            $email = $item->email;
            $status = $item->subscribe;

            if (!empty($name) OR !empty($email)) {
                $create = VonaSubscriber::firstOrCreate(
                    [
                        'email' => $email
                    ],
                    [
                        'name' => $name,
                        'status' => $status
                    ]
                );

                if ($create)
                {
                    $this->temptable('subs',$no);
                }
            }
        });

        $this->sendNotif('VONA Subscribers');

        $data = [
            'success' => 1,
            'message' => 'VONA Subscribers berhasil diperbarui',
            'count' => VonaSubscriber::count()
        ];

        return response()->json($data);
    }

    public function ven()
    {
        $vens = OldVen::whereBetween('erupt_id',[$this->startNo('ven'),$this->endNo('ven')])->get();

        $vens->each(function ($item, $Key){
            $no = $item->erupt_id;
            $code = $item->ga_code;
            $date = $item->erupt_tgl;
            $time = $item->erupt_jam;
            $visiblity = $item->erupt_vis;
            $height = $item->erupt_tka;
            $wasap = $item->erupt_wrn == '-' ? null : explode(', ',$item->erupt_wrn);
            $intensitas = $item->erupt_int == '-' ? null : explode(', ',$item->erupt_int);
            $arahasap = $item->erupt_arh == '-' ? null : explode(', ',$item->erupt_arh);
            $amp = $item->erupt_amp;
            $durasi = $item->erupt_drs;
            $photo = $item->erupt_pht;
            $photo == '-' ? $photo = null : $photo;
            $status = $item->erupt_sta;
            switch ($status) {
                case 'Level I (Normal)':
                    $status = '1';
                    break;
                case 'Level II (Waspada)':
                   $status = '2';
                    break;
                case 'Level III (Siaga)':
                    $status = '3';
                    break;
                default:
                    $status = '4';
                    break;
            }
            $rekomendasi = $item->erupt_rek;
            $lainnya = $item->erupt_ket;
            $lainnya == '-' ? $lainnya = null : $lainnya;
            $nip_pelapor = $item->erupt_usr;

            $create = MagmaVen::firstOrCreate(
                    [
                        'code_id' => $code,
                        'date' => $date,
                        'time' => $time
                    ],
                    [
                        'height' => $height,
                        'wasap' => $wasap,
                        'intensitas' => $intensitas,
                        'visibility' => $visiblity,
                        'arah_asap' => $arahasap,
                        'amplitudo' => $amp,
                        'durasi' => $durasi,        
                        'photo' => $photo,
                        'status' => $status,
                        'rekomendasi' => $rekomendasi,
                        'lainnya' => $lainnya,
                        'nip_pelapor' => $nip_pelapor
                    ]
                );

            if ($create)
            {
                $this->temptable('ven',$no);
            }
        });

        $this->sendNotif('Data Informasi Letusan');

        $data = [
            'success' => 1,
            'message' => 'Data Informasi Letusan berhasil diperbarui',
            'count' => MagmaVen::count()
        ];

        return response()->json($data);
    }

    /**
     * Import data Gerakan Tanah dari v1
     * table magma_crs, magma_sigertan, magma_qls_rec
     * qls_fst, qls_ver, qls_atm, qls_trb
     *
     * @return \Illuminate\Http\Request
     */

    public function crs()
    {

        $items = OldCrs::whereBetween('idx',[$this->startNo('crs'),$this->endNo('crs')])->get();

        foreach($items as $item)
        {
            $no             = $item->idx;
            $name           = $item->crs_usr;
            $phone          = strlen($item->crs_pho) >14 ? substr($item->crs_pho, 0, 14) : $item->crs_pho;
            $crs_id         = $item->crs_ids;
            $waktu_kejadian = $item->crs_dtm;
            $zona           = $item->crs_zon;
            $type           = $item->crs_typ == 'GUNUNGAPI' ? 'GUNUNG API': $item->crs_typ;
            $type           = $type == 'GEMPABUMI' ? 'GEMPA BUMI': $type;       
            $province_id    = Indonesia::search($item->crs_prv)->allProvinces()->first()->id;
            $city_id        = Indonesia::search($item->crs_cty)->allCities()->first()->id;
            $district_id    = Indonesia::search($item->crs_rgn)->allDistricts()->first()->id;
            $village_id     = Indonesia::search($item->crs_vil)->allVillages()->first()->id;
            $bwd            = $item->crs_bwd;
            $latitude       = $item->crs_lat > 1000000 ? $item->crs_lat/1000 : $item->crs_lat;
            $longitude      = $item->crs_lon > 50000 ? $item->crs_lon/100 : $item->crs_lon;
            $brd            = $item->crs_brd == 'TIDAK' ? 0 : 1;
            $sumber         = $item->crs_fsr;
            $tsc            = $item->crs_tsc;
            $ksc            = $item->crs_ksc;
            $status         = $item->crs_sta;
            $lat_user       = $item->lat_usr == null ? 0 : $item->lat_usr;
            $lon_user       = $item->long_usr == null ? 0 : $item->long_usr;
            
            $crs    = SigertanCrs::firstOrCreate(
                        [
                            'crs_id' => $crs_id
                        ],
                        [
                            'name' => $name,
                            'phone' => $phone,
                            'waktu_kejadian' => $waktu_kejadian,
                            'zona' => $zona,
                            'type' => $type,
                            'province_id' => $province_id,
                            'city_id' => $city_id,
                            'district_id' => $district_id,
                            'village_id' => $village_id,
                            'bwd' => $bwd,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'brd' => $brd,
                            'sumber' => $sumber,
                            'tsc' => $tsc,
                            'ksc' => $ksc,
                            'status' => $status,
                            'latitude_user' => $lat_user,
                            'longitde_user' => $lon_user
                        ]
                    );

            $aplikasi   = $item->crs_soa;
            $device     = $item->crs_dvc;

            $device = SigertanCrsDevices::firstOrCreate(
                            ['crs_id' => $crs->id],
                            [
                                'aplikasi' => $aplikasi,
                                'devices' => $device,
                            ]
                        );

            if (!empty($item->crs_vor))
            {
                $valid = $item->crs_val == 'VALID' ? 1 : 0;
                $nip_id = $item->crs_vor;

                $validasi = SigertanCrsValidasi::firstOrCreate(
                    ['crs_id' => $crs->id],
                    [
                        'valid' => $valid,
                        'nip_id' => $nip_id
                    ]
                );
            }

            $this->temptable('crs',$no);
           
        }       

        $this->sendNotif('Data CRS');

        $data = [
            'success' => 1,
            'message' => 'Data CRS berhasil diperbarui',
            'count' => SigertanCrs::count()
        ];

        return response()->json($data);

    }

    public function sigertan()
    {
        $qlses = OldSigertan::paginate(5);

        return $qlses;
    }

    /**
     * Import data Gempa Bumi dari v1
     * table magma_roq
     *
     * @return \Illuminate\Http\Request
     */
    public function roq()
    {
        $olds = OldRoq::whereBetween('no',[$this->startNo('roq'),$this->endNo('roq')])->get();

        $olds->each(function ($item,$key) {
            $no = $item->no;
            $utc = $item->datetime_utc;
            $noticenumber = $item->id_lap;
            $magnitude = $item->magnitude;
            $type = 'SR';
            $depth = $item->depth;
            $lat = $item->lat_lima;
            $lon = $item->lon_lima;
            $area = $item->area;
            $koter = $item->koter;
            $mmi = $item->mmi == '-belum ada keterangan-' ? null : $item->mmi;
            $nearest = $item->nearest_volcano;
            
            if (empty($item->roq_nip_pelapor)  AND !empty($item->roq_nip_pemeriksa))
            {
                $nip_pelapor = $item->roq_nip_pemeriksa;
            } else {
                $nip_pelapor = $item->roq_nip_pelapor;
            }

            $maplink = str_replace('https://magma.vsi.esdm.go.id/img/roqfm/','',$item->roq_maplink);

            $create = MagmaRoq::firstOrCreate(
                [ 'noticenumber' => $noticenumber ],
                [
                    'utc' => $utc,
                    'magnitude' => $magnitude,
                    'type' => $type,
                    'depth' => $depth,
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'area' => $area,
                    'kota_terdekat' => $koter,
                    'mmi' => $mmi,
                    'nearest_volcano' => $nearest
                ]
            );

            if ($item->roq_tanggapan == 'YA')
            {
                $response = RoqTanggapan::firstOrCreate(
                    [
                        'noticenumber_id' => $noticenumber
                    ],
                    [
                        'judul' => $item->roq_title,
                        'tsunami' => $item->roq_tsu == 'YA'? 1 : 0 ,
                        'pendahuluan' => $item->roq_intro,
                        'kondisi_wilayah' => $item->roq_konwil,
                        'mekanisme' => $item->roq_mekanisme,
                        'dampak' => $item->roq_efek,
                        'rekomendasi' => $item->roq_rekom,
                        'sumber' => explode(';',$item->roq_source),
                        'maplink' => $maplink,
                        'nip_pelapor' => $nip_pelapor,
                        'nip_pemeriksa' => $item->roq_nip_pemeriksa,
                    ]
                );
            }

           if ($create)
            {
                $this->temptable('roq',$no);
            }
        });

        $this->sendNotif('Data Gempa Bumi');

        $data = [
            'success' => 1,
            'message' => 'Data Gempa Bumi berhasil diperbarui',
            'count' => MagmaRoq::count()
        ];

        return response()->json($data);
    }

    /**
     * Index import
     *
     * @return void
     */
    public function index()
    {

        $users = User::count();
        $bidang = UserBidang::count();
        $gadds = Gadd::count();
        $varsv1 = DB::connection('magma')->table('magma_var')->count();
        $vars = MagmaVar::count();
        $vardailies = VarDaily::count();
        $visuals = VarVisual::count();
        $klimatologis = VarKlimatologi::count();
        $gempa = new VarGempa();
        $gempa = $gempa->jumlah();
        $crs = SigertanCrs::count();
        $vona = Vona::count();
        $subs = VonaSubscriber::count();
        $vens = MagmaVen::count();
        $roq =  MagmaRoq::count();
        $absensi = Absensi::count();
        
        return view('import.index',compact(
            'users',
            'bidang',
            'gadds',
            'varsv1',
            'vars',
            'vardailies',
            'visuals',
            'klimatologis',
            'gempa',
            'crs',
            'vona',
            'subs',
            'vens',
            'roq',
            'absensi'
            )
        );
    }
}
