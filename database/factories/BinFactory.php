<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bin;
use Faker\Generator as Faker;

$factory->define(Bin::class, function (Faker $faker) {
    return [
        'bin' => $faker->randomNumber(6),
        'bank' => $faker->words(2, true),
    ];
});
