<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ThxUser;
use Faker\Generator as Faker;

$factory->define(ThxUser::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});
