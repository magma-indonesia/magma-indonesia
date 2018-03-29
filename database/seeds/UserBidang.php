<?php

use Illuminate\Database\Seeder;

class UserBidang extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_bidang_descs')->insert([

            ['code' => 'PVG','nama' => 'Pusat Vulkanologi dan Mitigasi Bencana Geologi'],
            ['code' => 'MGA','nama' => 'Mitigasi Gunung Api'],
            ['code' => 'MGT','nama' => 'Mitigasi Gerakan Tanah'],
            ['code' => 'MGB','nama' => 'Mitigasi Gempa Bumi dan Tsunami'],
            ['code' => 'BPT','nama' => 'Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi'],
            ['code' => 'BTU','nama' => 'Tata Usaha']

        ]);
    }
}
