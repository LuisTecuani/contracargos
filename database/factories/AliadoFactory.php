<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AliadoBillingUsers;
use App\UserTdcAliado;
use App\Repsaliado;
use App\RespuestaBanorteAliado;
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
    $tarjeta = $faker->creditCardNumber;
    return [
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta, -4, 4),
        'user_id' => $faker->randomNumber(6),
        'fecha' => $faker->date('Y-m-d'),
        'autorizacion' => $faker->randomNumber(6),
        'monto' => $faker->randomNumber(4),
        'source_file' => Str::random(24),
    ];
});

$factory->define(RespuestaBanorteAliado::class, function (Faker $faker) {
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
        $aut = $faker->randomNumber(6);
    }
    $uId = $faker->randomNumber(6);
    $tarjeta = $faker->creditCardNumber;
    $date = $faker->date($format = 'Y-m-d');
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

$factory->define(AliadoBillingUsers::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'procedence' => $faker->name,
        'exp_date' => $faker->date('y-m'),
    ];
});
