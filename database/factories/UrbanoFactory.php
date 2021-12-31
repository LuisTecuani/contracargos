<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UrbanoAffinitas;
use App\UrbanoBillingUsers;
use App\UrbanoBlacklist;
use App\UrbanoCancelAccountAnswer;
use App\UrbanoPaycypsBill;
use App\UrbanoUser;
use App\UrbanoUserCancellation;
use App\UserTdcUrbano;
use App\Repsurbano;
use App\ContracargosUrbano;
use App\ContracargosUrbanoBanorte;
use App\RespuestasBanorteUrbano;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(UserTdcUrbano::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'number' => $faker->creditCardNumber,
        'exp_month' => $faker->date('m'),
        'exp_year' => $faker->date('Y'),
    ];
});

$factory->define(UrbanoUser::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(UrbanoCancelAccountAnswer::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
    ];
});

$factory->define(Repsurbano::class, function (Faker $faker) {
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
        'detalle_mensaje' => $dMensaje,
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'estatus' => $estatus,
        'user_id' => $uId = $faker->randomNumber(6),
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta, -4, 4),
        'monto' => 79,
        'fecha' => $faker->date($format = 'Y-m-d', $max = '-01 days'),
        'source_file' => Str::random(24),
    ];
});

$factory->define(RespuestasBanorteUrbano::class, function (Faker $faker) {
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
        'source_file' => "urbano-banorte-$date-Respuestas",
    ];
});

$factory->define(UrbanoBillingUsers::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'procedence' => $faker->name,
        'exp_date' => $faker->date('y-m'),
        'number' => $faker->creditCardNumber,
    ];
});

$factory->define(UrbanoUser::class, function (Faker $faker) {

    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(ContracargosUrbanoBanorte::class, function (Faker $faker) {

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

$factory->define(ContracargosUrbano::class, function (Faker $faker) {

    $date = $faker->date($format = 'Y-m-d', $max = '-1 days');
    return [
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'tarjeta' => $faker->randomNumber(4),
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
        'fecha_rep' => $date,
    ];
});

$factory->define(UrbanoBlacklist::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
    ];
});

$factory->define(UrbanoUserCancellation::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(6),
        'reason_id' => $faker->numberBetween($min = 1, $max = 55),
    ];
});

$factory->define(UrbanoPaycypsBill::class, function (Faker $faker) {
    return [
        'user_id' => UrbanoUser::class,
        'tdc' => $faker->creditCardNumber,
        'amount' => 9000,
        'bill_day' => $faker->dayOfMonth,
        'file_name' => 'fake-file-name',
        'paycyps_id' => '12_'.$faker->randomNumber(3),
    ];
});

$factory->define(UrbanoAffinitas::class, function (Faker $faker) {
    return [
        'user_id' => UrbanoUser::class,
        'tdc' => $faker->creditCardNumber,
        'amount' => 9000,
        'bill_day' => $faker->dayOfMonth,
    ];
});
