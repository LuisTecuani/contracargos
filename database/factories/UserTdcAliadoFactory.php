<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserTdcAliado;
use Faker\Generator as Faker;

$factory->define(UserTdcAliado::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'number' => $faker->creditCardNumber,
        'exp_month' => $faker->date('m'),
        'exp_year' => $faker->date('Y'),
    ];
});
