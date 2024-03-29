<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Repsthx;
use App\RespuestasBanorteThx;
use App\ThxUser;
use App\UserTdcThx;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$factory->define(ThxUser::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(UserTdcThx::class, function (Faker $faker) {

    return [
        'user_id' => $faker->randomNumber(6),
        'number' => $faker->creditCardNumber,
        'exp_date' => $faker->date('my'),
    ];
});

$factory->define(Repsthx::class, function (Faker $faker) {
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
        'estatus' => $estatus,
        'detalle_mensaje' => $dMensaje,
        'terminacion' => substr($tarjeta, -4, 4),
        'user_id' => factory(ThxUser::class)->create()->id,
        'fecha' => $faker->date($format = 'Y-m-d', $max = '-01 days'),
        'autorizacion' => $faker->numberBetween($min = 100000, $max = 999999),
        'monto' => $faker->randomNumber(4),
        'source_file' => Str::random(24),
    ];
});

$factory->define(RespuestasBanorteThx::class, function (Faker $faker) {
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
        'source_file' => "thx-banorte-$date-Respuestas",
    ];
});
