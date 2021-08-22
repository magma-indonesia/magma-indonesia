<?php

namespace App\Console\Commands;

use App\EventType;
use Illuminate\Console\Command;

class UpdateEventType extends Command
{

    protected $codes = [
        'lts' => 'Letusan/Erupsi',
        'apl' => 'Awan Panas Letusan',
        'apg' => 'Awan Panas Guguran',
        'gug' => 'Guguran',
        'hbs' => 'Hembusan',
        'hrm' => 'Harmonik',
        'tre' => 'Tremor Non-Harmonik',
        'tor' => 'Tornillo',
        'lof' => 'Low Frequency',
        'hyb' => 'Hybrid/Fase Banyak',
        'vtb' => 'Vulkanik Dangkal',
        'vta' => 'Vulkanik Dalam',
        'vlp' => 'Very Long Period',
        'tel' => 'Tektonik Lokal',
        'trs' => 'Terasa',
        'tej' => 'Tektonik Jauh',
        'dev' => 'Double Event',
        'gtb' => 'Getaran Banjir',
        'dpt' => 'Deep Tremor',
        'mtr' => 'Tremor Menerus'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:event_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi data kode dan nama jenis-jenis gempa yang digunakan dalam laporan MAGMA-VAR';

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
        $this->info('Updating Data Jenis Gempa Gunung Api....');
        $events = collect($this->codes);
        $events->each(function($name, $code) {
            EventType::firstOrCreate([
                'code' => $code,
            ],[
                'name' => $name,
            ]);
        });
        $this->info('Update berhasil');
    }
}
