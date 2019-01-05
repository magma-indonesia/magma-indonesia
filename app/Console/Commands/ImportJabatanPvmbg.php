<?php

namespace App\Console\Commands;

use App\Jabatan;
use Illuminate\Console\Command;

class ImportJabatanPvmbg extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:jabatan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan daftar Jabatan PVMBG';

    protected $jabatan = [
        'Analis Kepegawaian Muda',
        'Analis Kepegawaian Pelaksana Lanjutan',
        'Analis Kepegawaian Penyelia',
        'Analis Kimia',
        'Arsiparis Penyelia',
        'Bendahara',
        'Kasubbag Perencanaan & Keuangan',
        'Kasubbag Umum & Kepegawaian',
        'Kepala Bagian Tata Usaha',
        'Kepala Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi',
        'Kepala Bidang Mitigasi Gempa Bumi & Tsunami',
        'Kepala Bidang Mitigasi Gerakan Tanah',
        'Kepala Bidang Mitigasi Gunungapi',
        'Kepala Pusat Vulkanologi dan Mitigasi Bencana Geologi',
        'Kepala Seksi Gunung Merapi',
        'Kepala Seksi Metode dan Teknologi',
        'Kepala Seksi Pengelolaan Laboratorium',
        'Kepala Sub Bagian Tata Usaha',
        'Kepala Subbidang Mitigasi Gempa Bumi & Tsunami Wilayah Barat',
        'Kepala Subbidang Mitigasi Gempa Bumi dan Tsunami Wilayah Timur',
        'Kepala Subbidang Mitigasi Gerakan Tanah Wilayah Barat',
        'Kepala Subbidang Mitigasi Gerakan Tanah Wilayah Timur',
        'Kepala Subbidang Mitigasi Gunung Api Wil Barat',
        'Kepala Subbidang Mitigasi Gunung Api Wil Timur',
        'Pemantau Gunungapi',
        'Peneliti Madya',
        'Peneliti Muda',
        'Pengadministrasi Kepegawaian',
        'Pengadministrasi Keuangan',
        'Pengadministrasi Layanan Informasi dan Publikasi',
        'Pengadministrasi Umum',
        'Pengamatan Gunung Api Pelaksana',
        'Pengamatan Gunung Api Pelaksana Lanjutan',
        'Pengamatan Gunung Api Pelaksana Pemula',
        'Pengamatan Gunung Api Penyelia',
        'Pengelola BMN',
        'Pengelola Informasi',
        'Pengelola Kepegawaian',
        'Pengelola Keprotokolan',
        'Pengelola Perbendaharaan',
        'Pengelola Sarana Teknik',
        'Pengelola Umum',
        'Penyelidik Bumi Madya',
        'Penyelidik Bumi Muda',
        'Penyelidik Bumi Pertama',
        'Penyelidik Bumi Utama',
        'Penyelidik Geologi',
        'Perekayasa Madya',
        'Perekayasa Utama',
        'Pramu Pustaka',
        'Pranata Humas Muda',
        'Surveyor Pemetaan Penyelia',
        'Teknisi Kerumahtanggaan',
        'Teknisi Laboratorium',
        'Teknisi Litkayasa Pelaksana',
        'Teknisi Litkayasa Pelaksana Lanjutan',
        'Teknisi Litkayasa Penyelia',
        'Teknisi Survei',
    ];

    protected function import()
    {
        $this->jabatan->each(function ($nama, $key){
            Jabatan::firstOrCreate(['nama' => $nama]);
        });
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->jabatan = collect($this->jabatan);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Daftar Jabatan sedang dicopy ke dalam database ...');
        $this->import();
        $this->info('Berhasil menambahkan Jabatan');
    }
}
