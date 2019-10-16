<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\KameraGunungApi;

class UpdateCCTV extends Command
{
    protected $cctvs = 
    [
        ['code' => 'SIN',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Sinabung/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SIN',
          'lokasi' => 'Kebayekan',
          'url' => '/monitoring/CCTV/Kebayaken/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SIN',
          'lokasi' => 'Laukawar',
          'url' => '/monitoring/CCTV/Laukawar/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SIN',
          'lokasi' => 'Tigapancur',
          'url' => '/monitoring/CCTV/Tigapancur/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'MAR',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Marapi/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'TAL',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Talang/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'KRA',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Krakatau/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'KRA',
          'lokasi' => 'Puncak Krakatau',
          'url' => '/monitoring/CCTV/Krakatau/Latest/cam_2.jpg',
          'publish' => true
        ],
        ['code' => 'GED',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Gede/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'TPR',
          'lokasi' => 'Tangkuban Parahu',
          'url' => '/monitoring/CCTV/TangkubanParahu/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'GUN',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Guntur/Latest/Guntur.jpg',
          'publish' => true
        ],
        ['code' => 'PAP',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Papandayan/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'GAL',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Galunggung/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SLA',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Slamet/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'DIE',
          'lokasi' => 'Timbang',
          'url' => '/monitoring/CCTV/Dieng/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'DIE',
          'lokasi' => 'Sileri',
          'url' => '/monitoring/CCTV/DiengSileri/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SBG',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Sumbing/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SUN',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Sundoro/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'MER',
          'lokasi' => 'Puncak Merapi',
          'url' => '/monitoring/CCTV/Merapi/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'MER',
          'lokasi' => 'Thermal',
          'url' => '/monitoring/CCTV/Merapi/Latest/cam_2.jpg',
          'publish' => true
        ],
        ['code' => 'KLD',
          'lokasi' => 'Puncak Kelud',
          'url' => '/monitoring/CCTV/Kelut/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'BRO',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Bromo/Latest/bromo_1.jpg',
          'publish' => true
        ],
        ['code' => 'SMR',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Semeru/Latest/SemeruPOS.jpg',
          'publish' => true
        ],
        ['code' => 'SMR',
          'lokasi' => 'Sriti',
          'url' => '/monitoring/CCTV/Semeru/Latest/SemeruSriti.jpg',
          'publish' => true
        ],
        ['code' => 'IJE',
          'lokasi' => 'Kawah Ijen',
          'url' => '/monitoring/CCTV/Ijen/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'AGU',
          'lokasi' => 'Batu Lompeh',
          'url' => '/monitoring/CCTV/AgungBatulompeh/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'LOK',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/monitoring/CCTV/Lokon/Latest/cam_1.jpg',
          'publish' => true
        ],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cctv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update data CCTV';

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
        $this->info('Updating Data CCTV....');

        foreach ($this->cctvs as $cctv) {
            KameraGunungApi::create([
                'code' => $cctv['code'],
                'lokasi' => $cctv['lokasi'],
                'url' => $cctv['url'],
                'publish' => $cctv['publish'],
            ]);
        }

        $this->info('Update Data berhasil');
    }
}
