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
            'name'      => env('ADMIN_NAME','Admin'),
            'nip'       => env('ADMIN_NIP','000000000000000000'),
            'email'     => env('ADMIN_EMAIL','magma@esdm.go.id'),
            'phone'     => env('ADMIN_PHONE','0227272606'),
            'status'    => env('ADMIN_STATUS',1),
            'password'  => bcrypt(env('ADMIN_PASSWORD','admin')),
        ]);
    }
}
