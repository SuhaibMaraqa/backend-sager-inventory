<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'id' => 1,
        'title' => 'admin'
        //admin 1
        //center 2
        //pilot 3
    ];
});
