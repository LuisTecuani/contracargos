<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AliadoBillingUsers;
use App\AliadoBlacklist;
use App\AliadoCancelAccountAnswer;
use App\AliadoPaycypsBill;
use App\AliadoUser;
use App\AliadoUserCancellation;
use App\UserTdcAliado;
use App\Repsaliado;
use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
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

$factory->define(AliadoCancelAccountAnswer::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
    ];
});

$factory->define(Repsaliado::class, function (Faker $faker) {
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

$factory->define(RespuestasBanorteAliado::class, function (Faker $faker) {
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

$factory->define(ContracargosAliadoBanorte::class, function (Faker $faker) {

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

$factory->define(ContracargosAliado::class, function (Faker $faker) {

    $date = $faker->date($format = 'd-m-Y', $max = '-01 days');
    return [
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'tarjeta' => $faker->randomNumber(4),
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
        'fecha_rep' => $date,
    ];
});

$factory->define(AliadoBlacklist::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(6),
        'email' => $faker->email,
    ];
});

$factory->define(AliadoUserCancellation::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(6),
        'reason_id' => $faker->numberBetween($min = 1, $max = 55),
    ];
});

$factory->define(AliadoPaycypsBill::class, function (Faker $faker) {
    return [
        'user_id' => AliadoUser::class,
        'tdc' => $faker->creditCardNumber,
        'amount' => 9000,
        'bill_day' => $faker->dayOfMonth,
        'file_name' => 'fake-file-name',
        'paycyps_id' => '12_'.$faker->randomNumber(3),
    ];
});
