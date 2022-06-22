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
          'url' => '/storage/cctv/Sinabung/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SIN',
          'lokasi' => 'Kebayekan',
          'url' => '/storage/cctv/Kebayaken/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SIN',
          'lokasi' => 'Laukawar',
          'url' => '/storage/cctv/Laukawar/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SIN',
          'lokasi' => 'Tigapancur',
          'url' => '/storage/cctv/Tigapancur/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'MAR',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Marapi/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'TAL',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Talang/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'KRA',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Krakatau/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'KRA',
          'lokasi' => 'Puncak Krakatau',
          'url' => '/storage/cctv/Krakatau/Latest/cam_2.jpg',
          'publish' => true
        ],
        ['code' => 'GED',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Gede/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'TPR',
          'lokasi' => 'Tangkuban Parahu',
          'url' => '/storage/cctv/TangkubanParahu/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'GUN',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Guntur/Latest/Guntur.jpg',
          'publish' => true
        ],
        ['code' => 'PAP',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Papandayan/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'GAL',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Galunggung/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SLA',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Slamet/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'DIE',
          'lokasi' => 'Timbang',
          'url' => '/storage/cctv/Dieng/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'DIE',
          'lokasi' => 'Sileri',
          'url' => '/storage/cctv/DiengSileri/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SBG',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Sumbing/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'SUN',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Sundoro/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'MER',
          'lokasi' => 'Puncak Merapi',
          'url' => '/storage/cctv/Merapi/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'MER',
          'lokasi' => 'Thermal',
          'url' => '/storage/cctv/Merapi/Latest/cam_2.jpg',
          'publish' => true
        ],
        ['code' => 'KLD',
          'lokasi' => 'Puncak Kelud',
          'url' => '/storage/cctv/Kelut/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'BRO',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Bromo/Latest/bromo_1.jpg',
          'publish' => true
        ],
        ['code' => 'SMR',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Semeru/Latest/SemeruPOS.jpg',
          'publish' => true
        ],
        ['code' => 'SMR',
          'lokasi' => 'Sriti',
          'url' => '/storage/cctv/Semeru/Latest/SemeruSriti.jpg',
          'publish' => true
        ],
        ['code' => 'IJE',
          'lokasi' => 'Kawah Ijen',
          'url' => '/storage/cctv/Ijen/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'AGU',
          'lokasi' => 'Batu Lompeh',
          'url' => '/storage/cctv/AgungBatulompeh/Latest/cam_1.jpg',
          'publish' => true
        ],
        ['code' => 'LOK',
          'lokasi' => 'Pos Pengamatan',
          'url' => '/storage/cctv/Lokon/Latest/cam_1.jpg',
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
            KameraGunungApi::updateOrCreate([
                'code' => $cctv['code'],
                'lokasi' => $cctv['lokasi'],
            ],[
                'url' => $cctv['url'],
                'publish' => $cctv['publish'],
            ]);
        }

        $this->info('Update Data berhasil');
    }
}
