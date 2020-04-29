<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CellersUser;
use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use App\UserTdcCellers;
use App\CellersBillingUsers;
use App\Repscellers;
use App\RespuestasBanorteCellers;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(CellersUser::class, function (Faker $faker) {

    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(UserTdcCellers::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'number' => $faker->creditCardNumber,
        'exp_date' => $faker->date('my'),
    ];
});

$factory->define(Repscellers::class, function (Faker $faker) {
    $estatus = Arr::random(['Aprobada', 'Declinada']);
    if ($estatus != 'Aprobada') {
        $dMensaje = Arr::random([
            'Imposible autorizar en este momento',
            'Declinado general',
            'Fondos insuficientes',
            'Supera el monto límite permitido'
        ]);
    } else {
        $dMensaje = 'Aprobado';
    }
    $tarjeta = $faker->creditCardNumber;
    return [
        'tarjeta' => $tarjeta,
        'estatus' => $faker->word,
        'motivo_rechazo' => $faker->word,
        'terminacion' => substr($tarjeta, -4, 4),
        'user_id' => $faker->randomNumber(6),
        'fecha' => $faker->date($format = 'Y-m-d', $max = '-01 days'),
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'monto' => $faker->randomNumber(4),
        'source_file' => Str::random(24),
    ];
});

$factory->define(CellersBillingUsers::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'procedence' => $faker->name,
        'exp_date' => $faker->date('y-m'),
        'number' => $faker->creditCardNumber,
    ];
});

$factory->define(ContracargosCellers::class, function (Faker $faker) {

    $date = $faker->date($format = 'd-m-Y', $max = '-01 days');
    return [
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'tarjeta' => $faker->randomNumber(4),
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
        'fecha_rep' => $date,
    ];
});

$factory->define(RespuestasBanorteCellers::class, function (Faker $faker) {
    $estatus = Arr::random(['Aprobada', 'Declinada']);
    if ($estatus != 'Aprobada') {
        $dMensaje = Arr::random([
            'Imposible autorizar en este momento',
            'Declinado general',
            'Fondos insuficientes',
            'Supera el monto límite permitido'
        ]);
    } else {
        $dMensaje = 'Aprobado';
        $aut = $faker->numberBetween($min = 100000, $max = 999999);
    }
    $tarjeta = $faker->creditCardNumber;
    $date = $faker->date($format = 'Y-m-d', $max = '-01 days');
    $uId = $faker->randomNumber(6);
    return [
        'comentarios' => 'Cargo unico',
        'detalle_mensaje' => $dMensaje,
        'autorizacion' => $aut ?? null,
        'estatus' => $estatus,
        'user_id' => $uId,
        'num_control' => DateTime::createFromFormat('Ymd', $date) . $uId,
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta, -4, 4),
        'monto' => 79,
        'fecha' => $date,
        'source_file' => "aliado-banorte-$date-Respuestas",
    ];
});


$factory->define(ContracargosCellersBanorte::class, function (Faker $faker) {

    $date = $faker->date($format = 'd-m-Y', $max = '-01 days');
    return [
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'tarjeta' => $faker->randomNumber(4),
        'fecha_consumo' => $date,
        'fecha_contracargo' => '17-01-2020',
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
        'fecha_rep' => $date,
    ];
});

$factory->define(ContracargosCellers::class, function (Faker $faker) {

    $date = $faker->date($format = 'd-m-Y', $max = '-01 days');
    return [
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'tarjeta' => $faker->randomNumber(4),
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
        'fecha_rep' => $date,
    ];
});
