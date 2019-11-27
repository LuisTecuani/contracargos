<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CellersUser;
use App\Repscellers;
use App\ContracargosCellers;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(CellersUser::class, function (Faker $faker) {

    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(Repscellers::class, function (Faker $faker) {
    $tarjeta = $faker->creditCardNumber;
    return [
        'tarjeta' => $tarjeta,
        'estatus' => $faker->word,
        'motivo_rechazo' => $faker->word,
        'terminacion' => substr($tarjeta, -4, 4),
        'user_id' => $faker->randomNumber(6),
        'fecha' => $faker->date('Y-m-d'),
        'autorizacion' => $faker->randomNumber(6),
        'monto' => $faker->randomNumber(4),
        'source_file' => Str::random(24),
    ];
});

$factory->define(ContracargosCellers::class, function (Faker $faker) {

    return [
        'autorizacion' => $faker->randomNumber(6),
        'tarjeta' => $faker->randomNumber(4),
    ];
});
