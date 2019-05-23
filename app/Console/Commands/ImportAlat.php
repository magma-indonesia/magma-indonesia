<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Alat;
use App\AlatJenis;
use Log;

class ImportAlat extends Command
{

    protected $jenis = [
        'Seismometer',
        'Tiltmeter',
        'GPS',
        'EDM',
        'CTD',
        'DOAS',
        'MultiGas',
        'CCTV',
        'Infrasound',
        'Repeater',
        'Rain Gauge'
    ];

    protected $alat = [
        [
          'code_id' => 'LOK',
          'pos' => 'Lokon',
          'jenis' => 1,
          'kode_alat' => 'KIN',
          'latitude' => 1.36683333,
          'longitude' => 124.8163889,
          'elevasi' => 914,
          'status' => 1
        ],
        [
          'code_id' => 'LOK',
          'pos' => 'Lokon',
          'jenis' => 1,
          'kode_alat' => 'TTW',
          'latitude' => 1.36108333,
          'longitude' => 124.7936111,
          'elevasi' => 1336,
          'status' => 0
        ],
        [
          'code_id' => 'LOK',
          'pos' => 'Lokon',
          'jenis' => 1,
          'kode_alat' => 'SEA',
          'latitude' => 1.37013889,
          'longitude' => 124.7997222,
          'elevasi' => 1190,
          'status' => 0
        ],
        [
          'code_id' => 'LOK',
          'pos' => 'Lokon',
          'jenis' => 10,
          'kode_alat' => 'SEAR',
          'latitude' => 1.37013889,
          'longitude' => 124.7997222,
          'elevasi' => 1190,
          'status' => 0
        ],
        [
          'code_id' => 'LOK',
          'pos' => 'Lokon',
          'jenis' => 1,
          'kode_alat' => 'EMP',
          'latitude' => 1.36833333,
          'longitude' => 124.8002778,
          'elevasi' => 1177,
          'status' => 1
        ],
        [
          'code_id' => 'LOK',
          'pos' => 'Lokon',
          'jenis' => 1,
          'kode_alat' => 'WLN',
          'latitude' => 1.34957132,
          'longitude' => 124.8019444,
          'elevasi' => 1134.87,
          'status' => 1
        ],
        [
          'code_id' => 'MAH',
          'pos' => 'Mahawu',
          'jenis' => 1,
          'kode_alat' => 'MHW',
          'latitude' => 1.3518,
          'longitude' => 124.8596,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'MAH',
          'pos' => 'Mahawu',
          'jenis' => 1,
          'kode_alat' => 'MAH',
          'latitude' => 1.355,
          'longitude' => 124.8633,
          'elevasi' => 1319,
          'status' => 1
        ],
        [
          'code_id' => 'MAH',
          'pos' => 'Mahawu',
          'jenis' => 10,
          'kode_alat' => 'MAHR',
          'latitude' => 1.355,
          'longitude' => 124.8633,
          'elevasi' => 1319,
          'status' => 1
        ],
        [
          'code_id' => 'TGK',
          'pos' => 'Tangkoko',
          'jenis' => 1,
          'kode_alat' => 'PIN',
          'latitude' => 1.50463889,
          'longitude' => 125.2122222,
          'elevasi' => 512,
          'status' => 0
        ],
        [
          'code_id' => 'TGK',
          'pos' => 'Tangkoko',
          'jenis' => 1,
          'kode_alat' => 'TAK',
          'latitude' => 1.45933333,
          'longitude' => 125.2011111,
          'elevasi' => 42,
          'status' => 1
        ],
        [
          'code_id' => 'SOP',
          'pos' => 'Soputan',
          'jenis' => 1,
          'kode_alat' => 'SOP',
          'latitude' => 1.12255556,
          'longitude' => 124.7416667,
          'elevasi' => 1469,
          'status' => 1
        ],
        [
          'code_id' => 'SOP',
          'pos' => 'Soputan',
          'jenis' => 1,
          'kode_alat' => 'RIN',
          'latitude' => 1.13211111,
          'longitude' => 124.7472222,
          'elevasi' => 1469,
          'status' => 1
        ],
        [
          'code_id' => 'SOP',
          'pos' => 'Soputan',
          'jenis' => 1,
          'kode_alat' => 'SLN',
          'latitude' => 1.12691667,
          'longitude' => 124.7194444,
          'elevasi' => 984,
          'status' => 0
        ],
        [
          'code_id' => 'SOP',
          'pos' => 'Soputan',
          'jenis' => 1,
          'kode_alat' => 'WNR',
          'latitude' => 1.09727778,
          'longitude' => 124.7344444,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'AMB',
          'pos' => 'Ambang',
          'jenis' => 1,
          'kode_alat' => 'AMB1',
          'latitude' => 0.76216667,
          'longitude' => 124.4186111,
          'elevasi' => 1469,
          'status' => 1
        ],
        [
          'code_id' => 'AMB',
          'pos' => 'Ambang',
          'jenis' => 1,
          'kode_alat' => 'AMB2',
          'latitude' => 0.7524,
          'longitude' => 124.4208333,
          'elevasi' => 1469,
          'status' => 0
        ],
        [
          'code_id' => 'COL',
          'pos' => 'Colo',
          'jenis' => 1,
          'kode_alat' => 'KOLOLIO',
          'latitude' => -0.20565556,
          'longitude' => 121.6388889,
          'elevasi' => 26,
          'status' => 1
        ],
        [
          'code_id' => 'COL',
          'pos' => 'Colo',
          'jenis' => 1,
          'kode_alat' => 'BESOA',
          'latitude' => -0.1532,
          'longitude' => 121.6330556,
          'elevasi' => 115,
          'status' => 0
        ],
        [
          'code_id' => 'AWU',
          'pos' => 'Awu',
          'jenis' => 1,
          'kode_alat' => 'AWU1',
          'latitude' => 3.67106667,
          'longitude' => 125.4561111,
          'elevasi' => 1220,
          'status' => 1
        ],
        [
          'code_id' => 'AWU',
          'pos' => 'Awu',
          'jenis' => 1,
          'kode_alat' => 'AWU2',
          'latitude' => 3.65948333,
          'longitude' => 125.4625,
          'elevasi' => 607,
          'status' => 0
        ],
        [
          'code_id' => 'AWU',
          'pos' => 'Awu',
          'jenis' => 1,
          'kode_alat' => 'BEUHA',
          'latitude' => 3.69058333,
          'longitude' => 125.4977778,
          'elevasi' => 184,
          'status' => 0
        ],
        [
          'code_id' => 'AWU',
          'pos' => 'Awu',
          'jenis' => 1,
          'kode_alat' => 'KENDAHEU',
          'latitude' => 3.6799,
          'longitude' => 125.4166667,
          'elevasi' => 349,
          'status' => 0
        ],
        [
          'code_id' => 'KAR',
          'pos' => 'Karangetang',
          'jenis' => 1,
          'kode_alat' => 'AKB',
          'latitude' => 2.76119444,
          'longitude' => 125.3947222,
          'elevasi' => 814,
          'status' => 1
        ],
        [
          'code_id' => 'KAR',
          'pos' => 'Karangetang',
          'jenis' => 1,
          'kode_alat' => 'BBL',
          'latitude' => 2.73557778,
          'longitude' => 125.4069444,
          'elevasi' => 136,
          'status' => 1
        ],
        [
          'code_id' => 'KAR',
          'pos' => 'Karangetang',
          'jenis' => 1,
          'kode_alat' => 'KWG',
          'latitude' => 2.79572222,
          'longitude' => 125.3816667,
          'elevasi' => 222,
          'status' => 1
        ],
        [
          'code_id' => 'KAR',
          'pos' => 'Karangetang',
          'jenis' => 1,
          'kode_alat' => 'LIA',
          'latitude' => 2.77058056,
          'longitude' => 125.4558333,
          'elevasi' => 110,
          'status' => 0
        ],
        [
          'code_id' => 'KAR',
          'pos' => 'Karangetang',
          'jenis' => 10,
          'kode_alat' => 'KARR',
          'latitude' => 2.77077778,
          'longitude' => 125.4558333,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'RUA',
          'pos' => 'Ruang',
          'jenis' => 1,
          'kode_alat' => 'RUA',
          'latitude' => 2.31908333,
          'longitude' => 125.3675,
          'elevasi' => 239,
          'status' => 1
        ],
        [
          'code_id' => 'RUA',
          'pos' => 'Ruang',
          'jenis' => 1,
          'kode_alat' => 'LPT',
          'latitude' => 2.30347222,
          'longitude' => 125.3508333,
          'elevasi' => 125,
          'status' => 0
        ],
        [
          'code_id' => 'RUA',
          'pos' => 'Ruang',
          'jenis' => 1,
          'kode_alat' => 'TKL',
          'latitude' => 2.29233333,
          'longitude' => 125.3763889,
          'elevasi' => 59,
          'status' => 0
        ],
        [
          'code_id' => 'RUA',
          'pos' => 'Ruang',
          'jenis' => 1,
          'kode_alat' => 'POS',
          'latitude' => 2.32166667,
          'longitude' => 125.4083333,
          'elevasi' => 38,
          'status' => 0
        ],
        [
          'code_id' => 'RUA',
          'pos' => 'Ruang',
          'jenis' => 10,
          'kode_alat' => 'LHGR',
          'latitude' => 2.31941667,
          'longitude' => 125.3566667,
          'elevasi' => 76,
          'status' => 0
        ],
        [
          'code_id' => 'RUA',
          'pos' => 'Ruang',
          'jenis' => 10,
          'kode_alat' => 'RUAR',
          'latitude' => 2.35355556,
          'longitude' => 125.4013889,
          'elevasi' => 493,
          'status' => 0
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'TMKS',
          'latitude' => -8.36388889,
          'longitude' => 115.4666667,
          'elevasi' => 1216,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'PSAG',
          'latitude' => -8.37777778,
          'longitude' => 115.4986111,
          'elevasi' => 1306,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'CEGI',
          'latitude' => -8.30222222,
          'longitude' => 115.4716667,
          'elevasi' => 966,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'DUKU',
          'latitude' => -8.29583333,
          'longitude' => 115.5344444,
          'elevasi' => 630,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'YHKR',
          'latitude' => -8.38166667,
          'longitude' => 115.5083333,
          'elevasi' => 1196,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'ABNG',
          'latitude' => -8.29444444,
          'longitude' => 115.4347222,
          'elevasi' => 1535,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'DAMP',
          'latitude' => -8.34277778,
          'longitude' => 115.5572222,
          'elevasi' => 785,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 10,
          'kode_alat' => 'RUBY',
          'latitude' => -8.26916667,
          'longitude' => 115.5819444,
          'elevasi' => 67,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 3,
          'kode_alat' => 'TMKS',
          'latitude' => -8.36388889,
          'longitude' => 115.4666667,
          'elevasi' => 1216,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 3,
          'kode_alat' => 'CEGI',
          'latitude' => -8.30222222,
          'longitude' => 115.4716667,
          'elevasi' => 966,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 3,
          'kode_alat' => 'DUKU',
          'latitude' => -8.29583333,
          'longitude' => 115.5344444,
          'elevasi' => 630,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 3,
          'kode_alat' => 'YHKR',
          'latitude' => -8.38166667,
          'longitude' => 115.5083333,
          'elevasi' => 1196,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 10,
          'kode_alat' => 'ABNG',
          'latitude' => -8.29444444,
          'longitude' => 115.4347222,
          'elevasi' => 1535,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 3,
          'kode_alat' => 'Pos Rendang',
          'latitude' => -8.425,
          'longitude' => 115.425,
          'elevasi' => 536,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'BATU',
          'latitude' => -8.20861111,
          'longitude' => 115.4994444,
          'elevasi' => 61,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 2,
          'kode_alat' => 'YHKR',
          'latitude' => -8.38166667,
          'longitude' => 115.5083333,
          'elevasi' => 1196,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 3,
          'kode_alat' => 'PGBN',
          'latitude' => -8.36388889,
          'longitude' => 115.4613889,
          'elevasi' => 1174,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'Pos Rendang',
          'latitude' => -8.425,
          'longitude' => 115.425,
          'elevasi' => 536,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 1,
          'kode_alat' => 'GTOH',
          'latitude' => -8.34361111,
          'longitude' => 115.4861111,
          'elevasi' => 2395,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 11,
          'kode_alat' => 'Pos Rendang',
          'latitude' => -8.425,
          'longitude' => 115.425,
          'elevasi' => 536,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 8,
          'kode_alat' => 'Pos Rendang',
          'latitude' => -8.425,
          'longitude' => 115.425,
          'elevasi' => 536,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 2,
          'kode_alat' => 'PGBN',
          'latitude' => -8.36388889,
          'longitude' => 115.4613889,
          'elevasi' => 1174,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 2,
          'kode_alat' => 'CEGI',
          'latitude' => -8.30222222,
          'longitude' => 115.4716667,
          'elevasi' => 966,
          'status' => 1
        ],
        [
          'code_id' => 'AGU',
          'pos' => 'Agung',
          'jenis' => 8,
          'kode_alat' => 'Pos Batulompeh',
          'latitude' => -8.20861111,
          'longitude' => 115.4994444,
          'elevasi' => 61,
          'status' => 1
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 1,
          'kode_alat' => 'DNU',
          'latitude' => -8.2694444,
          'longitude' => 115.3852778,
          'elevasi' => 1045,
          'status' => 1
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 1,
          'kode_alat' => 'MPH',
          'latitude' => -8.24777778,
          'longitude' => 115.3458333,
          'elevasi' => 1121,
          'status' => 1
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 1,
          'kode_alat' => 'SGN',
          'latitude' => -8.23,
          'longitude' => 115.3886111,
          'elevasi' => 1205,
          'status' => 1
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 1,
          'kode_alat' => 'BTR',
          'latitude' => -8.24527778,
          'longitude' => 115.3763889,
          'elevasi' => 1436,
          'status' => 1
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 2,
          'kode_alat' => 'BTR',
          'latitude' => -8.24527778,
          'longitude' => 115.3763889,
          'elevasi' => 1436,
          'status' => 0
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 4,
          'kode_alat' => 'RGB01',
          'latitude' => -8.24308333,
          'longitude' => 115.3780556,
          'elevasi' => 1041,
          'status' => 0
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 4,
          'kode_alat' => 'RGB02',
          'latitude' => -8.24495,
          'longitude' => 115.3741667,
          'elevasi' => 1085,
          'status' => 0
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 4,
          'kode_alat' => 'RGB03',
          'latitude' => -8.24675,
          'longitude' => 115.3691667,
          'elevasi' => 1224,
          'status' => 0
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 11,
          'kode_alat' => 'Pos Batur',
          'latitude' => -8.29444444,
          'longitude' => 115.365,
          'elevasi' => 1312,
          'status' => 1
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 4,
          'kode_alat' => 'Pos Batur',
          'latitude' => -8.28744444,
          'longitude' => 115.3786111,
          'elevasi' => 1325,
          'status' => 0
        ],
        [
          'code_id' => 'BAT',
          'pos' => 'Batur',
          'jenis' => 5,
          'kode_alat' => 'CTD1',
          'latitude' => -8.26086111,
          'longitude' => 115.3944444,
          'elevasi' => 1040,
          'status' => 1
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 1,
          'kode_alat' => 'TENG',
          'latitude' => -8.37116667,
          'longitude' => 116.4633333,
          'elevasi' => 1627,
          'status' => 1
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 1,
          'kode_alat' => 'TOGK',
          'latitude' => -8.39536667,
          'longitude' => 116.5013889,
          'elevasi' => 1674,
          'status' => 1
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 1,
          'kode_alat' => 'PLWG',
          'latitude' => -8.38016667,
          'longitude' => 116.4463889,
          'elevasi' => 2222,
          'status' => 1
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 1,
          'kode_alat' => 'BARI',
          'latitude' => -8.39805556,
          'longitude' => 116.4161111,
          'elevasi' => 2012,
          'status' => 1
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 8,
          'kode_alat' => 'CCTV PLWG',
          'latitude' => -8.38474444,
          'longitude' => 116.4419444,
          'elevasi' => 2392,
          'status' => 0
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 10,
          'kode_alat' => 'Repeater PLWG',
          'latitude' => -8.38474444,
          'longitude' => 116.4419444,
          'elevasi' => 2392,
          'status' => 1
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 4,
          'kode_alat' => 'Pos PGA Rinjani',
          'latitude' => -8.35666667,
          'longitude' => 116.5216667,
          'elevasi' => 1141,
          'status' => 0
        ],
        [
          'code_id' => 'RIN',
          'pos' => 'Rinjani',
          'jenis' => 11,
          'kode_alat' => 'Pos PGA Rinjani',
          'latitude' => -8.35666667,
          'longitude' => 116.5216667,
          'elevasi' => 1141,
          'status' => 1
        ],
        [
          'code_id' => 'SAN',
          'pos' => 'Sangeangapi',
          'jenis' => 11,
          'kode_alat' => 'Pos PGA Sangeangapi',
          'latitude' => -8.29777778,
          'longitude' => 118.9355556,
          'elevasi' => 30,
          'status' => 1
        ],
        [
          'code_id' => 'SAN',
          'pos' => 'Sangeangapi',
          'jenis' => 1,
          'kode_alat' => 'SOLA',
          'latitude' => -8.16633333,
          'longitude' => 119.0194444,
          'elevasi' => 118,
          'status' => 1
        ],
        [
          'code_id' => 'SAN',
          'pos' => 'Sangeangapi',
          'jenis' => 1,
          'kode_alat' => 'SURI',
          'latitude' => -8.2202,
          'longitude' => 119.0230556,
          'elevasi' => 260,
          'status' => 0
        ],
        [
          'code_id' => 'SAN',
          'pos' => 'Sangeangapi',
          'jenis' => 4,
          'kode_alat' => 'Pos PGA Sangeangapi',
          'latitude' => -8.29777778,
          'longitude' => 118.9355556,
          'elevasi' => 30,
          'status' => 1
        ],
        [
          'code_id' => 'TAM',
          'pos' => 'Tambora',
          'jenis' => 11,
          'kode_alat' => 'Pos PGA Tambora',
          'latitude' => -8.34619444,
          'longitude' => 117.8241667,
          'elevasi' => 43,
          'status' => 1
        ],
        [
          'code_id' => 'TAM',
          'pos' => 'Tambora',
          'jenis' => 1,
          'kode_alat' => 'TEKO',
          'latitude' => -8.29086389,
          'longitude' => 117.9788889,
          'elevasi' => 1906,
          'status' => 1
        ],
        [
          'code_id' => 'TAM',
          'pos' => 'Tambora',
          'jenis' => 1,
          'kode_alat' => 'KALO',
          'latitude' => -8.29786111,
          'longitude' => 117.9972222,
          'elevasi' => 1760,
          'status' => 0
        ],
        [
          'code_id' => 'TAM',
          'pos' => 'Tambora',
          'jenis' => 1,
          'kode_alat' => 'HODO',
          'latitude' => -8.30083333,
          'longitude' => 118.0441667,
          'elevasi' => 1446,
          'status' => 0
        ],
        [
          'code_id' => 'TAM',
          'pos' => 'Tambora',
          'jenis' => 10,
          'kode_alat' => 'DORO CANGA',
          'latitude' => -8.44377778,
          'longitude' => 117.9719444,
          'elevasi' => 23,
          'status' => 0
        ],
        [
          'code_id' => 'TAM',
          'pos' => 'Tambora',
          'jenis' => 1,
          'kode_alat' => 'PCSL',
          'latitude' => -8.21847222,
          'longitude' => 117.9197222,
          'elevasi' => 1415,
          'status' => 0
        ],
        [
          'code_id' => 'IYA',
          'pos' => 'Iya',
          'jenis' => 1,
          'kode_alat' => 'Iya2',
          'latitude' => -8.8875,
          'longitude' => 121.6428889,
          'elevasi' => 480,
          'status' => 1
        ],
        [
          'code_id' => 'IYA',
          'pos' => 'Iya',
          'jenis' => 1,
          'kode_alat' => 'Iya2',
          'latitude' => -8.8875,
          'longitude' => 121.6428889,
          'elevasi' => 480,
          'status' => 1
        ],
        [
          'code_id' => 'IYA',
          'pos' => 'Iya',
          'jenis' => 1,
          'kode_alat' => 'Iya3',
          'latitude' => -8.883333333,
          'longitude' => 121.6353056,
          'elevasi' => 223,
          'status' => 1
        ],
        [
          'code_id' => 'IYA',
          'pos' => 'Iya',
          'jenis' => 1,
          'kode_alat' => 'Pos PGA Iya',
          'latitude' => -8.865944444,
          'longitude' => 121.6351944,
          'elevasi' => 45,
          'status' => 1
        ],
        [
          'code_id' => 'KLM',
          'pos' => 'Kelimutu',
          'jenis' => 1,
          'kode_alat' => 'Puncak',
          'latitude' => -8.766305556,
          'longitude' => 121.8108056,
          'elevasi' => 1669,
          'status' => 1
        ],
        [
          'code_id' => 'KLM',
          'pos' => 'Kelimutu',
          'jenis' => 1,
          'kode_alat' => 'Nuabaru',
          'latitude' => -8.761611111,
          'longitude' => 121.8359722,
          'elevasi' => 1111,
          'status' => 1
        ],
        [
          'code_id' => 'KLM',
          'pos' => 'Kelimutu',
          'jenis' => 1,
          'kode_alat' => 'Pos Kelimutu',
          'latitude' => -8.744166667,
          'longitude' => 121.8367222,
          'elevasi' => 848,
          'status' => 1
        ],
        [
          'code_id' => 'ROK',
          'pos' => 'Rokatenda',
          'jenis' => 1,
          'kode_alat' => 'Ona',
          'latitude' => -8.350972222,
          'longitude' => 121.7225694,
          'elevasi' => 47,
          'status' => 1
        ],
        [
          'code_id' => 'ROK',
          'pos' => 'Rokatenda',
          'jenis' => 1,
          'kode_alat' => 'Pos PGA Rokatenda',
          'latitude' => -8.502277778,
          'longitude' => 121.7123056,
          'elevasi' => 8,
          'status' => 1
        ],
        [
          'code_id' => 'SAN',
          'pos' => 'Sangeangapi',
          'jenis' => 1,
          'kode_alat' => 'BLDA',
          'latitude' => -8.247,
          'longitude' => 119.0365,
          'elevasi' => 92,
          'status' => 0
        ],
        [
          'code_id' => 'SIR',
          'pos' => 'Sirung',
          'jenis' => 1,
          'kode_alat' => 'Sirung',
          'latitude' => -8.489786,
          'longitude' => 124.113111,
          'elevasi' => 466,
          'status' => 1
        ],
        [
          'code_id' => 'WER',
          'pos' => 'Ile Werung',
          'jenis' => 1,
          'kode_alat' => 'Werung',
          'latitude' => -8.522277,
          'longitude' => 123.557111,
          'elevasi' => 755,
          'status' => 1
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 1,
          'kode_alat' => 'LWL',
          'latitude' => -8.279,
          'longitude' => 123.533027,
          'elevasi' => 173,
          'status' => 0
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 1,
          'kode_alat' => 'WWR',
          'latitude' => -8.326916,
          'longitude' => 123.504777,
          'elevasi' => 135,
          'status' => 1
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 1,
          'kode_alat' => 'BGM',
          'latitude' => -8.254083,
          'longitude' => 123.479833,
          'elevasi' => 159,
          'status' => 0
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 1,
          'kode_alat' => 'BTP',
          'latitude' => -8.28253,
          'longitude' => 123.494408,
          'elevasi' => 722,
          'status' => 1
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 10,
          'kode_alat' => 'BOL',
          'latitude' => -8.355583,
          'longitude' => 123.2587,
          'elevasi' => 1228,
          'status' => 1
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 10,
          'kode_alat' => 'WWR',
          'latitude' => -8.326916,
          'longitude' => 123.504777,
          'elevasi' => 135,
          'status' => 1
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 8,
          'kode_alat' => 'Pos Ili Lewotolok',
          'latitude' => -8.318777,
          'longitude' => 123.474277,
          'elevasi' => 42,
          'status' => 1
        ],
        [
          'code_id' => 'LEW',
          'pos' => 'Ili Lewotolok',
          'jenis' => 11,
          'kode_alat' => 'Pos Ili Lewotolok',
          'latitude' => -8.318777,
          'longitude' => 123.474277,
          'elevasi' => 42,
          'status' => 1
        ],
        [
          'code_id' => 'EGO',
          'pos' => 'Egon',
          'jenis' => 1,
          'kode_alat' => 'Rotan',
          'latitude' => -8.68625,
          'longitude' => 122.4270278,
          'elevasi' => 953,
          'status' => 1
        ],
        [
          'code_id' => 'EGO',
          'pos' => 'Egon',
          'jenis' => 1,
          'kode_alat' => 'Habilogot',
          'latitude' => -8.645722222,
          'longitude' => 122.4433333,
          'elevasi' => 714,
          'status' => 1
        ],
        [
          'code_id' => 'EGO',
          'pos' => 'Egon',
          'jenis' => 1,
          'kode_alat' => 'Pos PGA Egon',
          'latitude' => -8.613888889,
          'longitude' => 122.4418333,
          'elevasi' => 36,
          'status' => 1
        ],
        [
          'code_id' => 'DUK',
          'pos' => 'Dukono',
          'jenis' => 1,
          'kode_alat' => 'DKN',
          'latitude' => 1.717239,
          'longitude' => 127.8751,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'IBU',
          'pos' => 'Ibu',
          'jenis' => 1,
          'kode_alat' => 'IBU',
          'latitude' => 1.503778,
          'longitude' => 127.6224,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'GMK',
          'pos' => 'Gamkonora',
          'jenis' => 1,
          'kode_alat' => 'GMK (Puncak)',
          'latitude' => 1.38245,
          'longitude' => 127.5341,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'GMK',
          'pos' => 'Gamkonora',
          'jenis' => 10,
          'kode_alat' => 'GMK 2',
          'latitude' => 1.393914,
          'longitude' => 127.5159,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'TLR',
          'latitude' => 0.824833,
          'longitude' => 127.3125,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'SLDR',
          'latitude' => 0.85525,
          'longitude' => 127.3334,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'KLB',
          'latitude' => 0.823028,
          'longitude' => 127.3515,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'ARK',
          'latitude' => 0.797639,
          'longitude' => 127.3539,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'ARB',
          'latitude' => 0.801686,
          'longitude' => 127.3528,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'FORA',
          'latitude' => 0.779189,
          'longitude' => 127.3278,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'NGDR',
          'latitude' => 0.761944,
          'longitude' => 127.3528,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 6,
          'kode_alat' => 'TKOM',
          'latitude' => 0.855464,
          'longitude' => 127.3263,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 3,
          'kode_alat' => 'TKOM',
          'latitude' => 0.855464,
          'longitude' => 127.3263,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 10,
          'kode_alat' => 'KLB2',
          'latitude' => 0.8228,
          'longitude' => 127.351,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 6,
          'kode_alat' => 'BANG',
          'latitude' => 0.829858,
          'longitude' => 127.3675,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 6,
          'kode_alat' => 'UMMU',
          'latitude' => 0.760139,
          'longitude' => 127.3285,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'NGD-BB',
          'latitude' => 0.769833,
          'longitude' => 127.3413,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 10,
          'kode_alat' => 'NGD-BB',
          'latitude' => 0.769833,
          'longitude' => 127.3413,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 10,
          'kode_alat' => 'NGDK',
          'latitude' => 0.761953,
          'longitude' => 127.3519,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 1,
          'kode_alat' => 'ARB',
          'latitude' => 0.801908,
          'longitude' => 127.35,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 3,
          'kode_alat' => 'ARB',
          'latitude' => 0.801908,
          'longitude' => 127.35,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'GML',
          'pos' => 'Gamalama',
          'jenis' => 10,
          'kode_alat' => 'ARB',
          'latitude' => 0.801908,
          'longitude' => 127.35,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'KIE',
          'pos' => 'Kie Besi',
          'jenis' => 1,
          'kode_alat' => 'KBESI',
          'latitude' => 0.352056,
          'longitude' => 127.4194,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'KIE',
          'pos' => 'Kie Besi',
          'jenis' => 1,
          'kode_alat' => 'KBESIR',
          'latitude' => 0.451139,
          'longitude' => 127.4292,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'BAN',
          'pos' => 'Banda Api',
          'jenis' => 10,
          'kode_alat' => 'Repeater',
          'latitude' => -4.53073,
          'longitude' => 129.8753,
          'elevasi' => 0,
          'status' => 0
        ],
        [
          'code_id' => 'WUR',
          'pos' => 'Wurlali',
          'jenis' => 1,
          'kode_alat' => 'PUNCAK',
          'latitude' => -7.14378,
          'longitude' => 128.6866,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sundoro',
          'jenis' => 8,
          'kode_alat' => 'POSS',
          'latitude' => -7.28316667,
          'longitude' => 110.0643333,
          'elevasi' => 1011,
          'status' => 1
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sundoro',
          'jenis' => 1,
          'kode_alat' => 'SDPL',
          'latitude' => -7.28977778,
          'longitude' => 110.01875,
          'elevasi' => 1859,
          'status' => 1
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sundoro',
          'jenis' => 1,
          'kode_alat' => 'SDPL',
          'latitude' => -7.28977778,
          'longitude' => 110.01875,
          'elevasi' => 1859,
          'status' => 1
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sundoro',
          'jenis' => 1,
          'kode_alat' => 'SBJK',
          'latitude' => -7.27500833,
          'longitude' => 109.9921944,
          'elevasi' => 1954,
          'status' => 1
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sundoro',
          'jenis' => 1,
          'kode_alat' => 'MLLN',
          'latitude' => -7.30058333,
          'longitude' => 110.0058417,
          'elevasi' => 2684,
          'status' => 0
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sundoro',
          'jenis' => 2,
          'kode_alat' => 'MLLN',
          'latitude' => -7.30058333,
          'longitude' => 110.0058417,
          'elevasi' => 2684,
          'status' => 0
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 1,
          'kode_alat' => 'BROB',
          'latitude' => -7.93875,
          'longitude' => 112.9605278,
          'elevasi' => 2202,
          'status' => 1
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 1,
          'kode_alat' => 'WIDB',
          'latitude' => -7.94806389,
          'longitude' => 112.9458333,
          'elevasi' => 2458,
          'status' => 0
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 2,
          'kode_alat' => 'BROB',
          'latitude' => -7.93875,
          'longitude' => 112.9605278,
          'elevasi' => 2202,
          'status' => 1
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 8,
          'kode_alat' => 'POSB',
          'latitude' => -7.92796667,
          'longitude' => 112.9687667,
          'elevasi' => 2275,
          'status' => 1
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 4,
          'kode_alat' => 'BROM',
          'latitude' => -7.93876389,
          'longitude' => 112.9605167,
          'elevasi' => 2202,
          'status' => 1
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 4,
          'kode_alat' => 'KURS',
          'latitude' => -7.94663889,
          'longitude' => 112.9646028,
          'elevasi' => 2161,
          'status' => 1
        ],
        [
          'code_id' => 'BRO',
          'pos' => 'Bromo',
          'jenis' => 4,
          'kode_alat' => 'BTOK',
          'latitude' => -7.93518611,
          'longitude' => 112.9531111,
          'elevasi' => 2204,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'KWR',
          'latitude' => 3.1916389,
          'longitude' => 98.3851389,
          'elevasi' => 1525,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'SKN',
          'latitude' => 3.1775278,
          'longitude' => 98.4141944,
          'elevasi' => 1468,
          'status' => 0
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'SKM',
          'latitude' => 3.1483056,
          'longitude' => 98.4010278,
          'elevasi' => 1301,
          'status' => 0
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'MDD',
          'latitude' => 3.1593056,
          'longitude' => 98.3663056,
          'elevasi' => 1179,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'SGG',
          'latitude' => 3.1870278,
          'longitude' => 98.4081667,
          'elevasi' => 1445,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'GBR',
          'latitude' => 3.1546111,
          'longitude' => 98.4361667,
          'elevasi' => 1207,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'KBY',
          'latitude' => 3.2212222,
          'longitude' => 98.4263611,
          'elevasi' => 1479,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Pusuk Bukit',
          'jenis' => 1,
          'kode_alat' => 'PBK',
          'latitude' => 2.6140278,
          'longitude' => 98.6691944,
          'elevasi' => 1233,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 10,
          'kode_alat' => 'RPT KWR',
          'latitude' => 3.2212222,
          'longitude' => 98.4263611,
          'elevasi' => 1479,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 10,
          'kode_alat' => 'RPT MDD',
          'latitude' => 3.0441111,
          'longitude' => 98.4175833,
          'elevasi' => 1105,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 10,
          'kode_alat' => 'RPT PBK',
          'latitude' => 3.2394722,
          'longitude' => 98.5021111,
          'elevasi' => 2011,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 1,
          'kode_alat' => 'SBY',
          'latitude' => 3.2394722,
          'longitude' => 98.5021111,
          'elevasi' => 2011,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 3,
          'kode_alat' => 'SKNL',
          'latitude' => 3.1775278,
          'longitude' => 98.4141944,
          'elevasi' => 1468,
          'status' => 0
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 3,
          'kode_alat' => 'LKWR',
          'latitude' => 3.1916389,
          'longitude' => 98.3851389,
          'elevasi' => 1525,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 3,
          'kode_alat' => 'MRDG',
          'latitude' => 3.1600278,
          'longitude' => 98.3668056,
          'elevasi' => 1214,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 3,
          'kode_alat' => 'GKRI',
          'latitude' => 3.14163889,
          'longitude' => 98.3851389,
          'elevasi' => 1525,
          'status' => 0
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 3,
          'kode_alat' => 'SNBG',
          'latitude' => 3.14108333,
          'longitude' => 98.4642972,
          'elevasi' => 1252,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 3,
          'kode_alat' => 'KBYK',
          'latitude' => 3.2212222,
          'longitude' => 98.4263611,
          'elevasi' => 1479,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 10,
          'kode_alat' => 'Refeater GPS',
          'latitude' => 3.0441111,
          'longitude' => 98.4175833,
          'elevasi' => 1105,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 9,
          'kode_alat' => 'POS PPGA',
          'latitude' => 3.14108333,
          'longitude' => 98.46429722,
          'elevasi' => 1252,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 2,
          'kode_alat' => 'SGRR',
          'latitude' => 3.1870278,
          'longitude' => 98.4081667,
          'elevasi' => 1445,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 2,
          'kode_alat' => 'MDD',
          'latitude' => 3.1600278,
          'longitude' => 98.3668056,
          'elevasi' => 1214,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 2,
          'kode_alat' => 'SKNA',
          'latitude' => 3.1775278,
          'longitude' => 98.4141944,
          'elevasi' => 1468,
          'status' => 0
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 2,
          'kode_alat' => 'KWR',
          'latitude' => 3.1916389,
          'longitude' => 98.3851389,
          'elevasi' => 1525,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 8,
          'kode_alat' => 'Laukawar',
          'latitude' => 3.1916389,
          'longitude' => 98.3851389,
          'elevasi' => 1525,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 8,
          'kode_alat' => 'POS PPGA',
          'latitude' => 3.14108333,
          'longitude' => 98.4642972,
          'elevasi' => 1252,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 4,
          'kode_alat' => 'SNB13 Reflektor',
          'latitude' => 3.1825278,
          'longitude' => 98.4051111,
          'elevasi' => 1560,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 4,
          'kode_alat' => 'SNB12 Alat',
          'latitude' => 3.1945556,
          'longitude' => 98.41025,
          'elevasi' => 1340,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 6,
          'kode_alat' => 'Simalem',
          'latitude' => 3.2093611,
          'longitude' => 98.4224278,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 6,
          'kode_alat' => 'Sukandebi',
          'latitude' => 3.1759889,
          'longitude' => 98.441,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'SIN',
          'pos' => 'Sinabung',
          'jenis' => 6,
          'kode_alat' => 'Jeraya',
          'latitude' => 3.13200833,
          'longitude' => 98.44415,
          'elevasi' => 0,
          'status' => 1
        ],
        [
          'code_id' => 'KAB',
          'pos' => 'Kaba',
          'jenis' => 1,
          'kode_alat' => 'Puncak',
          'latitude' => -3.509027,
          'longitude' => 102.626916,
          'elevasi' => 1800,
          'status' => 0
        ],
        [
          'code_id' => 'KAB',
          'pos' => 'Kaba',
          'jenis' => 1,
          'kode_alat' => 'Biring',
          'latitude' => -3.52638,
          'longitude' => 102.872,
          'elevasi' => 1829,
          'status' => 1
        ],
        [
          'code_id' => 'SUN',
          'pos' => 'Sumbing',
          'jenis' => 1,
          'kode_alat' => 'PTRG',
          'latitude' => -7.352333,
          'longitude' => 110.069167,
          'elevasi' => 1884,
          'status' => 1
        ]
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:alat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importing data peralatan monitoring Gunung Api (Data per April 2019)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getJenis()
    {
        return collect($this->jenis);
    }

    protected function getAlat()
    {
        return collect($this->alat)->map(function ($item, $key) {
                    $item['nama_alat'] = $item['kode_alat'];
                    $item['kode_alat'] = strlen($item['kode_alat']) > 4 ? null : $item['kode_alat'];
                    $item['nip'] = '198803152015031005';
                    return $item;
                });
    }

    protected function updateAlat()
    {
        $jenis = $this->getJenis()->each(function ($item, $key) {
            $jenis = AlatJenis::firstOrCreate(['jenis_alat' => $item]);
        });

        $alat = $this->getAlat()
                ->each(function ($item, $key) {
                    $alat = Alat::updateOrCreate(
                        [
                            'code_id' => $item['code_id'],
                            'jenis_id' => $item['jenis'],
                            'latitude' => $item['latitude']
                        ],
                        [
                            'kode_alat' => $item['kode_alat'],
                            'nama_alat' => $item['nama_alat'],
                            'longitude' => $item['longitude'],
                            'elevasi' => $item['elevasi'],
                            'status' => $item['status'],
                            'nip' => $item['nip'],
                        ]
                    );
                });

        return;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('['.now().'] Import Data Peralatan');
        $this->updateAlat();
        $this->info('['.now().'] Import Data Peralatan berhasil');
    }
}
