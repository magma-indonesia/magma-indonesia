<?php

use Illuminate\Database\Seeder;

class FungsionalsTableSeeder extends Seeder
{

    protected $fungsionals = [
        ['nama' => 'Perekayasa'],
        ['nama' => 'Struktural'],
        ['nama' => 'Penyelidik Bumi'],
        ['nama' => 'Peneliti'],
        ['nama' => 'Umum'],
        ['nama' => 'Analis Kepegawaian'],
        ['nama' => 'Arsiparis'],
        ['nama' => 'Pengamat Gunung Api'],
        ['nama' => 'Surveyor'],
        ['nama' => 'Litkayasa'],
        ['nama' => 'Pranata Humas'],
        ['nama' => 'Teknisi Litkayasa'],
        ['nama' => 'Pengelola Pengadaan Barang Dan Jasa'],
        ['nama' => 'Pengamat Gunung Api']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fungsionals')->insert($this->fungsionals);
    }
}
