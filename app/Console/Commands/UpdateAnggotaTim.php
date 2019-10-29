<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MGA\AnggotaKegiatan;

class UpdateAnggotaTim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:anggota_tim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update kegiatan_id di anggota tim';

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
        $this->info('Updating Data Kegiatan....');
        $anggotas = AnggotaKegiatan::all();

        foreach ($anggotas as $anggota) {
            $anggota->load('detail_kegiatan:id,kegiatan_id,start_date,end_date','detail_kegiatan.kegiatan.jenis_kegiatan:id,nama');
            $anggota->kegiatan_id = $anggota->detail_kegiatan->kegiatan->id;
            $anggota->save();
        }

        $this->info('Update Data berhasil');

    }
}
