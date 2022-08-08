<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => 'admin',
        'last_name' => 'admin',
        'email' => 'admin@admin.com',
        'email_verified_at' => now(),
        'password' => '$2y$10$utxfPrfFYtdu/zNyXB2bBubDs.i6BzBMwD6xvKIEzykU1TW5H/Tfu', // password
        'remember_token' => Str::random(10),
        'role_id' => 1,
        'gender' => $faker->randomElement($array = array('m', 'f')), // 'b'
        'national_id' => $faker->randomNumber($nbDigits = NULL, $strict = false)
    ];
});
