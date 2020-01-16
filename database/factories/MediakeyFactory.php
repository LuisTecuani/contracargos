<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ContracargosMediakey;
use App\ContracargosMediakeyBanorte;
use App\MediakeyBillingUsers;
use App\MediakeyUser;
use App\UserTdcMediakey;
use App\Repsmediakey;
use App\RespuestasBanorteMediakey;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(UserTdcMediakey::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'number' => $faker->creditCardNumber,
        'month' => $faker->date('m'),
        'year' => $faker->date('Y'),
    ];
});

$factory->define(Repsmediakey::class, function (Faker $faker) {
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
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'estatus' => $estatus,
        'user_id' => $uId,
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta, -4, 4),
        'monto' => 79,
        'fecha' => $date,
        'source_file' => Str::random(24),
    ];
});

$factory->define(RespuestasBanorteMediakey::class, function (Faker $faker) {
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
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'estatus' => $estatus,
        'user_id' => $uId,
        'num_control' => DateTime::createFromFormat('Ymd', $date) . $uId,
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta, -4, 4),
        'monto' => 79,
        'fecha' => $date,
        'source_file' => "mediakey-banorte-$date-Respuestas",
    ];
});

$factory->define(MediakeyBillingUsers::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'procedence' => $faker->name,
        'exp_date' => $faker->date('y-m'),
        'number' => $faker->creditCardNumber,
    ];
});

$factory->define(MediakeyUser::class, function (Faker $faker) {

    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(ContracargosMediakeyBanorte::class, function (Faker $faker) {

    $date = $faker->date($format = 'd-m-Y', $max = '16-01-2020');
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

$factory->define(ContracargosMediakey::class, function (Faker $faker) {

    $date = $faker->date($format = 'd-m-Y', $max = '16-01-2020');
    return [
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'tarjeta' => $faker->randomNumber(4),
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
        'fecha_rep' => $date,
    ];
});
