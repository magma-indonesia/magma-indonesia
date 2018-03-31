<?php

use Faker\Generator as Faker;

$factory->define(App\TesUserMgb::class, function (Faker $faker) {
    return [
        'name'=> $faker->name, 
        'nip' => $faker->randomNumber(16),
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->mobileNumber,
        'password' => bcrypt(123456),
        'status' => 1,
        'api_token' => str_random(10),
        'remember_token' => str_random(10),
    ];
});
