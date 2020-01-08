<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AliadoBillingUsers;
use App\AliadoUser;
use App\UserTdcAliado;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(UserTdcAliado::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'number' => $faker->creditCardNumber,
        'exp_month' => $faker->date('m'),
        'exp_year' => $faker->date('Y'),
    ];
});

$factory->define(Repsaliado::class, function (Faker $faker) {
    $estatus = Arr::random(['Aprobada', 'Declinada']);
    if ($estatus == 'Declinada') {
        $dMensaje = Arr::random([
            'Imposible autorizar en este momento',
            'Declinado general',
            'Fondos insuficientes',
            'Supera el monto límite permitido'
        ]);
    } else {
        $dMensaje = 'Aprobado';
    }
    $uId = $faker->randomNumber(6);
    $tarjeta = $faker->creditCardNumber;
    $date = $faker->date($format = 'Y-m-d');
    return [
        'motivo_rechazo' => $dMensaje,
        'autorizacion' => $faker->randomNumber(6),
        'estatus' => $estatus,
        'user_id' => $uId,
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta, -4, 4),
        'monto' => 79,
        'fecha' => $date,
        'source_file' => Str::random(24),
    ];
});

$factory->define(RespuestasBanorteAliado::class, function (Faker $faker) {
    $estatus = Arr::random(['Aprobada', 'Declinada']);
    if ($estatus == 'Declinada') {
        $dMensaje = Arr::random([
            'Imposible autorizar en este momento',
            'Declinado general',
            'Fondos insuficientes',
            'Supera el monto límite permitido'
        ]);
    } else {
        $dMensaje = 'Aprobado';
    }
    $uId = $faker->randomNumber(6);
    $tarjeta = $faker->creditCardNumber;
    $date = $faker->date($format = 'Y-m-d');
    return [
        'comentarios' => 'Cargo unico',
        'detalle_mensaje' => $dMensaje,
        'autorizacion' => $faker->randomNumber(6),
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

$factory->define(AliadoBillingUsers::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'procedence' => $faker->name,
        'exp_date' => $faker->date('y-m'),
        'number' => $faker->creditCardNumber,
    ];
});

$factory->define(AliadoUser::class, function (Faker $faker) {

    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});
