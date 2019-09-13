<?php

use Illuminate\Database\Seeder;

class JabatansTableSeeder extends Seeder
{
    protected $jabatan = [
        [
          'nama' => 'Perekayasa Utama'
        ],
        [
          'nama' => 'Kepala Pusat Vulkanologi dan Mitigasi Bencana Geologi'
        ],
        [
          'nama' => 'Penyelidik Bumi Madya'
        ],
        [
          'nama' => 'Penyelidik Bumi Utama'
        ],
        [
          'nama' => 'Perekayasa Madya'
        ],
        [
          'nama' => 'Kepala Bagian Tata Usaha'
        ],
        [
          'nama' => 'Kepala Bidang Mitigasi Gempa Bumi & Tsunami'
        ],
        [
          'nama' => 'Kepala Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi'
        ],
        [
          'nama' => 'Peneliti Madya'
        ],
        [
          'nama' => 'Pengelola Keprotokolan'
        ],
        [
          'nama' => 'Kepala Bidang Mitigasi Gunung Api'
        ],
        [
          'nama' => 'Kepala Subbidang Mitigasi Gunung Api Wil Barat'
        ],
        [
          'nama' => 'Pengelola Sarana Teknik'
        ],
        [
          'nama' => 'Penyelidik Geologi'
        ],
        [
          'nama' => 'Kepala Bidang Mitigasi Gerakan Tanah'
        ],
        [
          'nama' => 'Kepala Seksi Pengelolaan Laboratorium'
        ],
        [
          'nama' => 'Kasubbag Perencanaan & Keuangan'
        ],
        [
          'nama' => 'Kasubbag Umum & Kepegawaian'
        ],
        [
          'nama' => 'Kepala Seksi Gunung Merapi'
        ],
        [
          'nama' => 'Kepala Seksi Metode dan Teknologi'
        ],
        [
          'nama' => 'Kepala Subbidang Mitigasi Gempa Bumi & Tsunami Wilayah Barat'
        ],
        [
          'nama' => 'Kepala Subbidang Mitigasi Gempa Bumi dan Tsunami Wilayah Timur'
        ],
        [
          'nama' => 'Kepala Subbidang Mitigasi Gerakan Tanah Wilayah Barat'
        ],
        [
          'nama' => 'Kepala Subbidang Mitigasi Gerakan Tanah Wilayah Timur'
        ],
        [
          'nama' => 'Kepala Subbidang Mitigasi Gunung Api Wil Timur'
        ],
        [
          'nama' => 'Analis Kepegawaian Muda'
        ],
        [
          'nama' => 'Analis Kepegawaian Penyelia'
        ],
        [
          'nama' => 'Arsiparis Penyelia'
        ],
        [
          'nama' => 'Peneliti Muda'
        ],
        [
          'nama' => 'Pengamat Gunung Api Penyelia'
        ],
        [
          'nama' => 'Penyelidik Bumi Muda'
        ],
        [
          'nama' => 'Surveyor Pemetaan Penyelia'
        ],
        [
          'nama' => 'Teknisi Litkayasa Penyelia'
        ],
        [
          'nama' => 'Pengelola BMN'
        ],
        [
          'nama' => 'Pengelola Informasi'
        ],
        [
          'nama' => 'Pengelola Kepegawaian'
        ],
        [
          'nama' => 'Pengelola Perbendaharaan'
        ],
        [
          'nama' => 'Kepala Sub Bagian Tata Usaha'
        ],
        [
          'nama' => 'Penyelidik Bumi Pertama'
        ],
        [
          'nama' => 'Pranata Humas Muda'
        ],
        [
          'nama' => 'Pengadministrasi Umum'
        ],
        [
          'nama' => 'Analis Kepegawaian Pelaksana Lanjutan'
        ],
        [
          'nama' => 'Pengamat Gunung Api Pelaksana Lanjutan'
        ],
        [
          'nama' => 'Teknisi Litkayasa Pelaksana Lanjutan'
        ],
        [
          'nama' => 'Analis Kimia'
        ],
        [
          'nama' => 'Bendahara'
        ],
        [
          'nama' => 'Pengadministrasi Keuangan'
        ],
        [
          'nama' => 'Pengadministrasi Layanan Informasi dan Publikasi'
        ],
        [
          'nama' => 'Pengelola Umum'
        ],
        [
          'nama' => 'Pramu Pustaka'
        ],
        [
          'nama' => 'Teknisi Laboratorium'
        ],
        [
          'nama' => 'Teknisi Survei'
        ],
        [
          'nama' => 'Pengamat Gunung Api Pelaksana'
        ],
        [
          'nama' => 'Teknisi Litkayasa Pelaksana'
        ],
        [
          'nama' => 'Pemantau Gunung Api'
        ],
        [
          'nama' => 'Pengadministrasi Kepegawaian'
        ],
        [
          'nama' => 'Teknisi Kerumahtanggaan'
        ],
        [
          'nama' => 'Pengamat Gunung Api Pelaksana Pemula'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jabatans')->insert($this->jabatan);
    }
}
