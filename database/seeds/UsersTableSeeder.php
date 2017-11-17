<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'      => 'Martanto',
            'nip'       => '198803152015031005',
            'email'     => 'martanto@live.com',
            'phone'     => '085236600055',
            'status'    => 1,
            'password'  => bcrypt('anto')
        ]);
    }
}
