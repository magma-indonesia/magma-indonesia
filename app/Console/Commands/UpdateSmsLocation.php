<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSmsLocation extends Command
{
    protected $sms_locations = [
        [
            "code_id" => "AGU",
            "kode_kabupaten" => "5107",
            "nama_kabupaten" => "KABUPATEN KARANG ASEM"
        ],
        [
            "code_id" => "AGU",
            "kode_kabupaten" => "5105",
            "nama_kabupaten" => "KABUPATEN KLUNGKUNG"
        ],
        [
            "code_id" => "AGU",
            "kode_kabupaten" => "5106",
            "nama_kabupaten" => "KABUPATEN BANGLI"
        ],
        [
            "code_id" => "AMB",
            "kode_kabupaten" => "7110",
            "nama_kabupaten" => "KABUPATEN BOLAANG MONGONDOW SELATAN"
        ],
        [
            "code_id" => "AMB",
            "kode_kabupaten" => "7101",
            "nama_kabupaten" => "KABUPATEN BOLAANG MONGONDOW"
        ],
        [
            "code_id" => "AMB",
            "kode_kabupaten" => "7105",
            "nama_kabupaten" => "KABUPATEN MINAHASA SELATAN"
        ],
        [
            "code_id" => "AMB",
            "kode_kabupaten" => "7174",
            "nama_kabupaten" => "KOTA KOTAMOBAGU"
        ],
        [
            "code_id" => "RAN",
            "kode_kabupaten" => "5310",
            "nama_kabupaten" => "KABUPATEN SIKKA"
        ],
        [
            "code_id" => "RAN",
            "kode_kabupaten" => "5319",
            "nama_kabupaten" => "KABUPATEN MANGGARAI TIMUR"
        ],
        [
            "code_id" => "WEL",
            "kode_kabupaten" => "3514",
            "nama_kabupaten" => "KABUPATEN PASURUAN"
        ],
        [
            "code_id" => "WEL",
            "kode_kabupaten" => "3516",
            "nama_kabupaten" => "KABUPATEN MOJOKERTO"
        ],
        [
            "code_id" => "WEL",
            "kode_kabupaten" => "3525",
            "nama_kabupaten" => "KABUPATEN GRESIK"
        ],
        [
            "code_id" => "WEL",
            "kode_kabupaten" => "3507",
            "nama_kabupaten" => "KABUPATEN MALANG"
        ],
        [
            "code_id" => "AWU",
            "kode_kabupaten" => "7103",
            "nama_kabupaten" => "KABUPATEN KEPULAUAN SANGIHE"
        ],
        [
            "code_id" => "BAN",
            "kode_kabupaten" => "8101",
            "nama_kabupaten" => "KABUPATEN MALUKU TENGGARA BARAT"
        ],
        [
            "code_id" => "BAT",
            "kode_kabupaten" => "5106",
            "nama_kabupaten" => "KABUPATEN BANGLI"
        ],
        [
            "code_id" => "BRO",
            "kode_kabupaten" => "3507",
            "nama_kabupaten" => "KABUPATEN MALANG"
        ],
        [
            "code_id" => "BRO",
            "kode_kabupaten" => "3508",
            "nama_kabupaten" => "KABUPATEN LUMAJANG"
        ],
        [
            "code_id" => "BRO",
            "kode_kabupaten" => "3513",
            "nama_kabupaten" => "KABUPATEN PROBOLINGGO"
        ],
        [
            "code_id" => "BRO",
            "kode_kabupaten" => "3514",
            "nama_kabupaten" => "KABUPATEN PASURUAN"
        ],
        [
            "code_id" => "TEL",
            "kode_kabupaten" => "1104",
            "nama_kabupaten" => "KABUPATEN ACEH TENGGARA"
        ],
        [
            "code_id" => "TEL",
            "kode_kabupaten" => "1117",
            "nama_kabupaten" => "KABUPATEN BENER MERIAH"
        ],
        [
            "code_id" => "CER",
            "kode_kabupaten" => "3208",
            "nama_kabupaten" => "KABUPATEN KUNINGAN"
        ],
        [
            "code_id" => "CER",
            "kode_kabupaten" => "3210",
            "nama_kabupaten" => "KABUPATEN MAJALENGKA"
        ],
        [
            "code_id" => "CER",
            "kode_kabupaten" => "3209",
            "nama_kabupaten" => "KABUPATEN CIREBON"
        ],
        [
            "code_id" => "CER",
            "kode_kabupaten" => "3274",
            "nama_kabupaten" => "KOTA CIREBON"
        ],
        [
            "code_id" => "COL",
            "kode_kabupaten" => "7209",
            "nama_kabupaten" => "KABUPATEN TOJO UNA-UNA"
        ],
        [
            "code_id" => "DEM",
            "kode_kabupaten" => "1604",
            "nama_kabupaten" => "KABUPATEN LAHAT"
        ],
        [
            "code_id" => "DEM",
            "kode_kabupaten" => "1611",
            "nama_kabupaten" => "KABUPATEN EMPAT LAWANG"
        ],
        [
            "code_id" => "DEM",
            "kode_kabupaten" => "1672",
            "nama_kabupaten" => "KOTA PRABUMULIH"
        ],
        [
            "code_id" => "DIE",
            "kode_kabupaten" => "3304",
            "nama_kabupaten" => "KABUPATEN BANJARNEGARA"
        ],
        [
            "code_id" => "DIE",
            "kode_kabupaten" => "3307",
            "nama_kabupaten" => "KABUPATEN WONOSOBO"
        ],
        [
            "code_id" => "DIE",
            "kode_kabupaten" => "3325",
            "nama_kabupaten" => "KABUPATEN BATANG"
        ],
        [
            "code_id" => "DUK",
            "kode_kabupaten" => "8203",
            "nama_kabupaten" => "KABUPATEN KEPULAUAN SULA"
        ],
        [
            "code_id" => "EBU",
            "kode_kabupaten" => "5316",
            "nama_kabupaten" => "KABUPATEN SUMBA TENGAH"
        ],
        [
            "code_id" => "EBU",
            "kode_kabupaten" => "5309",
            "nama_kabupaten" => "KABUPATEN FLORES TIMUR"
        ],
        [
            "code_id" => "EGO",
            "kode_kabupaten" => "5307",
            "nama_kabupaten" => "KABUPATEN ALOR"
        ],
        [
            "code_id" => "GAL",
            "kode_kabupaten" => "3206",
            "nama_kabupaten" => "KABUPATEN TASIKMALAYA"
        ],
        [
            "code_id" => "GAL",
            "kode_kabupaten" => "3278",
            "nama_kabupaten" => "KOTA TASIKMALAYA"
        ],
        [
            "code_id" => "GAL",
            "kode_kabupaten" => "3205",
            "nama_kabupaten" => "KABUPATEN GARUT"
        ],
        [
            "code_id" => "GML",
            "kode_kabupaten" => "8271",
            "nama_kabupaten" => "KOTA TERNATE"
        ],
        [
            "code_id" => "GMK",
            "kode_kabupaten" => "8201",
            "nama_kabupaten" => "KABUPATEN HALMAHERA BARAT"
        ],
        [
            "code_id" => "GED",
            "kode_kabupaten" => "3202",
            "nama_kabupaten" => "KABUPATEN SUKABUMI"
        ],
        [
            "code_id" => "GED",
            "kode_kabupaten" => "3203",
            "nama_kabupaten" => "KABUPATEN CIANJUR"
        ],
        [
            "code_id" => "GED",
            "kode_kabupaten" => "3272",
            "nama_kabupaten" => "KOTA SUKABUMI"
        ],
        [
            "code_id" => "GED",
            "kode_kabupaten" => "3201",
            "nama_kabupaten" => "KABUPATEN BOGOR"
        ],
        [
            "code_id" => "GUN",
            "kode_kabupaten" => "3205",
            "nama_kabupaten" => "KABUPATEN GARUT"
        ],
        [
            "code_id" => "GUN",
            "kode_kabupaten" => "3204",
            "nama_kabupaten" => "KABUPATEN BANDUNG"
        ],
        [
            "code_id" => "IBU",
            "kode_kabupaten" => "8201",
            "nama_kabupaten" => "KABUPATEN HALMAHERA BARAT"
        ],
        [
            "code_id" => "IBU",
            "kode_kabupaten" => "8203",
            "nama_kabupaten" => "KABUPATEN KEPULAUAN SULA"
        ],
        [
            "code_id" => "IJE",
            "kode_kabupaten" => "3510",
            "nama_kabupaten" => "KABUPATEN BANYUWANGI"
        ],
        [
            "code_id" => "IJE",
            "kode_kabupaten" => "3511",
            "nama_kabupaten" => "KABUPATEN BONDOWOSO"
        ],
        [
            "code_id" => "IJE",
            "kode_kabupaten" => "3512",
            "nama_kabupaten" => "KABUPATEN SITUBONDO"
        ],
        [
            "code_id" => "WER",
            "kode_kabupaten" => "5313",
            "nama_kabupaten" => "KABUPATEN MANGGARAI"
        ],
        [
            "code_id" => "BOL",
            "kode_kabupaten" => "5306",
            "nama_kabupaten" => "KABUPATEN BELU"
        ],
        [
            "code_id" => "BOL",
            "kode_kabupaten" => "5313",
            "nama_kabupaten" => "KABUPATEN MANGGARAI"
        ],
        [
            "code_id" => "LEW",
            "kode_kabupaten" => "5306",
            "nama_kabupaten" => "KABUPATEN BELU"
        ],
        [
            "code_id" => "LEW",
            "kode_kabupaten" => "5313",
            "nama_kabupaten" => "KABUPATEN MANGGARAI"
        ],
        [
            "code_id" => "LIK",
            "kode_kabupaten" => "5309",
            "nama_kabupaten" => "KABUPATEN FLORES TIMUR"
        ],
        [
            "code_id" => "RIE",
            "kode_kabupaten" => "5309",
            "nama_kabupaten" => "KABUPATEN FLORES TIMUR"
        ],
        [
            "code_id" => "IYA",
            "kode_kabupaten" => "5308",
            "nama_kabupaten" => "KABUPATEN LEMBATA"
        ],
        [
            "code_id" => "KAB",
            "kode_kabupaten" => "1702",
            "nama_kabupaten" => "KABUPATEN REJANG LEBONG"
        ],
        [
            "code_id" => "KAB",
            "kode_kabupaten" => "1708",
            "nama_kabupaten" => "KABUPATEN KEPAHIANG"
        ],
        [
            "code_id" => "KAR",
            "kode_kabupaten" => "7109",
            "nama_kabupaten" => "KABUPATEN MINAHASA TENGGARA"
        ],
        [
            "code_id" => "KLM",
            "kode_kabupaten" => "5308",
            "nama_kabupaten" => "KABUPATEN LEMBATA"
        ],
        [
            "code_id" => "KLD",
            "kode_kabupaten" => "3505",
            "nama_kabupaten" => "KABUPATEN BLITAR"
        ],
        [
            "code_id" => "KLD",
            "kode_kabupaten" => "3506",
            "nama_kabupaten" => "KABUPATEN KEDIRI"
        ],
        [
            "code_id" => "KLD",
            "kode_kabupaten" => "3507",
            "nama_kabupaten" => "KABUPATEN MALANG"
        ],
        [
            "code_id" => "KLD",
            "kode_kabupaten" => "3504",
            "nama_kabupaten" => "KABUPATEN TULUNGAGUNG"
        ],
        [
            "code_id" => "KLD",
            "kode_kabupaten" => "3517",
            "nama_kabupaten" => "KABUPATEN JOMBANG"
        ],
        [
            "code_id" => "KLD",
            "kode_kabupaten" => "3526",
            "nama_kabupaten" => "KABUPATEN BANGKALAN"
        ],
        [
            "code_id" => "KER",
            "kode_kabupaten" => "1311",
            "nama_kabupaten" => "KABUPATEN DHARMASRAYA"
        ],
        [
            "code_id" => "KER",
            "kode_kabupaten" => "1501",
            "nama_kabupaten" => "KABUPATEN KERINCI"
        ],
        [
            "code_id" => "KIE",
            "kode_kabupaten" => "8204",
            "nama_kabupaten" => "KABUPATEN HALMAHERA SELATAN"
        ],
        [
            "code_id" => "LAM",
            "kode_kabupaten" => "3508",
            "nama_kabupaten" => "KABUPATEN LUMAJANG"
        ],
        [
            "code_id" => "LAM",
            "kode_kabupaten" => "3509",
            "nama_kabupaten" => "KABUPATEN JEMBER"
        ],
        [
            "code_id" => "LAM",
            "kode_kabupaten" => "3513",
            "nama_kabupaten" => "KABUPATEN PROBOLINGGO"
        ],
        [
            "code_id" => "LER",
            "kode_kabupaten" => "5306",
            "nama_kabupaten" => "KABUPATEN BELU"
        ],
        [
            "code_id" => "LWK",
            "kode_kabupaten" => "5306",
            "nama_kabupaten" => "KABUPATEN BELU"
        ],
        [
            "code_id" => "LWK",
            "kode_kabupaten" => "5306",
            "nama_kabupaten" => "KABUPATEN BELU"
        ],
        [
            "code_id" => "LWP",
            "kode_kabupaten" => "5306",
            "nama_kabupaten" => "KABUPATEN BELU"
        ],
        [
            "code_id" => "LOK",
            "kode_kabupaten" => "7102",
            "nama_kabupaten" => "KABUPATEN MINAHASA"
        ],
        [
            "code_id" => "LOK",
            "kode_kabupaten" => "7173",
            "nama_kabupaten" => "KOTA TOMOHON"
        ],
        [
            "code_id" => "LOK",
            "kode_kabupaten" => "7171",
            "nama_kabupaten" => "KOTA MANADO"
        ],
        [
            "code_id" => "MAH",
            "kode_kabupaten" => "7102",
            "nama_kabupaten" => "KABUPATEN MINAHASA"
        ],
        [
            "code_id" => "MAH",
            "kode_kabupaten" => "7173",
            "nama_kabupaten" => "KOTA TOMOHON"
        ],
        [
            "code_id" => "MAH",
            "kode_kabupaten" => "7106",
            "nama_kabupaten" => "KABUPATEN MINAHASA UTARA"
        ],
        [
            "code_id" => "MAH",
            "kode_kabupaten" => "7171",
            "nama_kabupaten" => "KOTA MANADO"
        ],
        [
            "code_id" => "MAR",
            "kode_kabupaten" => "1304",
            "nama_kabupaten" => "KABUPATEN SIJUNJUNG"
        ],
        [
            "code_id" => "MAR",
            "kode_kabupaten" => "1306",
            "nama_kabupaten" => "KABUPATEN PADANG PARIAMAN"
        ],
        [
            "code_id" => "MAR",
            "kode_kabupaten" => "1374",
            "nama_kabupaten" => "KOTA PADANG PANJANG"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3308",
            "nama_kabupaten" => "KABUPATEN MAGELANG"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3309",
            "nama_kabupaten" => "KABUPATEN BOYOLALI"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3310",
            "nama_kabupaten" => "KABUPATEN KLATEN"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3404",
            "nama_kabupaten" => "KABUPATEN SLEMAN"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3401",
            "nama_kabupaten" => "KABUPATEN KULON PROGO"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3471",
            "nama_kabupaten" => "KOTA YOGYAKARTA"
        ],
        [
            "code_id" => "MER",
            "kode_kabupaten" => "3322",
            "nama_kabupaten" => "KABUPATEN SEMARANG"
        ],
        [
            "code_id" => "PAP",
            "kode_kabupaten" => "3204",
            "nama_kabupaten" => "KABUPATEN BANDUNG"
        ],
        [
            "code_id" => "PAP",
            "kode_kabupaten" => "3205",
            "nama_kabupaten" => "KABUPATEN GARUT"
        ],
        [
            "code_id" => "PEU",
            "kode_kabupaten" => "1107",
            "nama_kabupaten" => "KABUPATEN ACEH BARAT"
        ],
        [
            "code_id" => "RAU",
            "kode_kabupaten" => "3509",
            "nama_kabupaten" => "KABUPATEN JEMBER"
        ],
        [
            "code_id" => "RAU",
            "kode_kabupaten" => "3510",
            "nama_kabupaten" => "KABUPATEN BANYUWANGI"
        ],
        [
            "code_id" => "RAU",
            "kode_kabupaten" => "3511",
            "nama_kabupaten" => "KABUPATEN BONDOWOSO"
        ],
        [
            "code_id" => "RIN",
            "kode_kabupaten" => "5202",
            "nama_kabupaten" => "KABUPATEN LOMBOK TENGAH"
        ],
        [
            "code_id" => "RIN",
            "kode_kabupaten" => "5203",
            "nama_kabupaten" => "KABUPATEN LOMBOK TIMUR"
        ],
        [
            "code_id" => "RIN",
            "kode_kabupaten" => "5208",
            "nama_kabupaten" => "KABUPATEN LOMBOK UTARA"
        ],
        [
            "code_id" => "ROK",
            "kode_kabupaten" => "5307",
            "nama_kabupaten" => "KABUPATEN ALOR"
        ],
        [
            "code_id" => "RUA",
            "kode_kabupaten" => "7109",
            "nama_kabupaten" => "KABUPATEN MINAHASA TENGGARA"
        ],
        [
            "code_id" => "SAL",
            "kode_kabupaten" => "3201",
            "nama_kabupaten" => "KABUPATEN BOGOR"
        ],
        [
            "code_id" => "SAL",
            "kode_kabupaten" => "3202",
            "nama_kabupaten" => "KABUPATEN SUKABUMI"
        ],
        [
            "code_id" => "SAL",
            "kode_kabupaten" => "3271",
            "nama_kabupaten" => "KOTA BOGOR"
        ],
        [
            "code_id" => "SAN",
            "kode_kabupaten" => "5206",
            "nama_kabupaten" => "KABUPATEN BIMA"
        ],
        [
            "code_id" => "SMR",
            "kode_kabupaten" => "3507",
            "nama_kabupaten" => "KABUPATEN MALANG"
        ],
        [
            "code_id" => "SMR",
            "kode_kabupaten" => "3508",
            "nama_kabupaten" => "KABUPATEN LUMAJANG"
        ],
        [
            "code_id" => "SEU",
            "kode_kabupaten" => "1106",
            "nama_kabupaten" => "KABUPATEN ACEH TENGAH"
        ],
        [
            "code_id" => "SIN",
            "kode_kabupaten" => "1206",
            "nama_kabupaten" => "KABUPATEN TOBA SAMOSIR"
        ],
        [
            "code_id" => "SIR",
            "kode_kabupaten" => "5305",
            "nama_kabupaten" => "KABUPATEN TIMOR TENGAH UTARA"
        ],
        [
            "code_id" => "SLA",
            "kode_kabupaten" => "3302",
            "nama_kabupaten" => "KABUPATEN BANYUMAS"
        ],
        [
            "code_id" => "SLA",
            "kode_kabupaten" => "3303",
            "nama_kabupaten" => "KABUPATEN PURBALINGGA"
        ],
        [
            "code_id" => "SLA",
            "kode_kabupaten" => "3327",
            "nama_kabupaten" => "KABUPATEN PEMALANG"
        ],
        [
            "code_id" => "SLA",
            "kode_kabupaten" => "3328",
            "nama_kabupaten" => "KABUPATEN TEGAL"
        ],
        [
            "code_id" => "SLA",
            "kode_kabupaten" => "3329",
            "nama_kabupaten" => "KABUPATEN BREBES"
        ],
        [
            "code_id" => "SOP",
            "kode_kabupaten" => "7102",
            "nama_kabupaten" => "KABUPATEN MINAHASA"
        ],
        [
            "code_id" => "SOP",
            "kode_kabupaten" => "7105",
            "nama_kabupaten" => "KABUPATEN MINAHASA SELATAN"
        ],
        [
            "code_id" => "SOP",
            "kode_kabupaten" => "7107",
            "nama_kabupaten" => "KABUPATEN BOLAANG MONGONDOW UTARA"
        ],
        [
            "code_id" => "SOR",
            "kode_kabupaten" => "1213",
            "nama_kabupaten" => "KABUPATEN LANGKAT"
        ],
        [
            "code_id" => "SBG",
            "kode_kabupaten" => "3306",
            "nama_kabupaten" => "KABUPATEN PURWOREJO"
        ],
        [
            "code_id" => "SBG",
            "kode_kabupaten" => "3307",
            "nama_kabupaten" => "KABUPATEN WONOSOBO"
        ],
        [
            "code_id" => "SBG",
            "kode_kabupaten" => "3308",
            "nama_kabupaten" => "KABUPATEN MAGELANG"
        ],
        [
            "code_id" => "SBG",
            "kode_kabupaten" => "3323",
            "nama_kabupaten" => "KABUPATEN TEMANGGUNG"
        ],
        [
            "code_id" => "SBG",
            "kode_kabupaten" => "3371",
            "nama_kabupaten" => "KOTA MAGELANG"
        ],
        [
            "code_id" => "SUN",
            "kode_kabupaten" => "3307",
            "nama_kabupaten" => "KABUPATEN WONOSOBO"
        ],
        [
            "code_id" => "SUN",
            "kode_kabupaten" => "3323",
            "nama_kabupaten" => "KABUPATEN TEMANGGUNG"
        ],
        [
            "code_id" => "TAL",
            "kode_kabupaten" => "1302",
            "nama_kabupaten" => "KABUPATEN PESISIR SELATAN"
        ],
        [
            "code_id" => "TAL",
            "kode_kabupaten" => "1372",
            "nama_kabupaten" => "KOTA SOLOK"
        ],
        [
            "code_id" => "TAM",
            "kode_kabupaten" => "5206",
            "nama_kabupaten" => "KABUPATEN BIMA"
        ],
        [
            "code_id" => "TAM",
            "kode_kabupaten" => "5205",
            "nama_kabupaten" => "KABUPATEN DOMPU"
        ],
        [
            "code_id" => "TAM",
            "kode_kabupaten" => "5206",
            "nama_kabupaten" => "KABUPATEN BIMA"
        ],
        [
            "code_id" => "TAN",
            "kode_kabupaten" => "1304",
            "nama_kabupaten" => "KABUPATEN SIJUNJUNG"
        ],
        [
            "code_id" => "TAN",
            "kode_kabupaten" => "1305",
            "nama_kabupaten" => "KABUPATEN TANAH DATAR"
        ],
        [
            "code_id" => "TAN",
            "kode_kabupaten" => "1306",
            "nama_kabupaten" => "KABUPATEN PADANG PARIAMAN"
        ],
        [
            "code_id" => "TAN",
            "kode_kabupaten" => "1374",
            "nama_kabupaten" => "KOTA PADANG PANJANG"
        ],
        [
            "code_id" => "TGK",
            "kode_kabupaten" => "7172",
            "nama_kabupaten" => "KOTA BITUNG"
        ],
        [
            "code_id" => "TPR",
            "kode_kabupaten" => "3204",
            "nama_kabupaten" => "KABUPATEN BANDUNG"
        ],
        [
            "code_id" => "TPR",
            "kode_kabupaten" => "3213",
            "nama_kabupaten" => "KABUPATEN SUBANG"
        ],
        [
            "code_id" => "TPR",
            "kode_kabupaten" => "3214",
            "nama_kabupaten" => "KABUPATEN PURWAKARTA"
        ],
        [
            "code_id" => "TPR",
            "kode_kabupaten" => "3217",
            "nama_kabupaten" => "KABUPATEN BANDUNG BARAT"
        ],
        [
            "code_id" => "TPR",
            "kode_kabupaten" => "3273",
            "nama_kabupaten" => "KOTA BANDUNG"
        ],
        [
            "code_id" => "TPR",
            "kode_kabupaten" => "3277",
            "nama_kabupaten" => "KOTA CIMAHI"
        ],
        [
            "code_id" => "WUR",
            "kode_kabupaten" => "8108",
            "nama_kabupaten" => "KABUPATEN MALUKU BARAT DAYA"
        ]
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:sms_location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating data sms locations untuk sms blast';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (DB::table('sms_locations')->count())
            return $this->info('Table SMS Locations telah terisi');

        $locations = collect($this->sms_locations);

        $bar = $this->output->createProgressBar(count($locations));
        $bar->start();

        $locations->each(function ($location) use ($bar) {
            DB::table('sms_locations')->insert($location);
            $bar->advance();
        });

        $bar->finish();
        return $this->info(' Update SMS Location berhasil');
    }
}
