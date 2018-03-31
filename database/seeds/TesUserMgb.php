<?php

use Illuminate\Database\Seeder;

class TesUserMgb extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 0; $i < 30; $i++) {
            App\TesUserMgb::create([
                'name'=> $faker->name, 
                'nip' => $faker->shuffle('1234567890123456'),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->shuffle('123456789000'),
                'password' => bcrypt(123456),
                'status' => 1,
                'api_token' => str_random(10),
                'remember_token' => str_random(10),
            ]);
        }
    }
}
