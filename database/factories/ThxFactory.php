<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ThxPaycypsBill;
use App\ThxUser;
use Faker\Generator as Faker;

$factory->define(ThxUser::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(6),
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(ThxPaycypsBill::class, function (Faker $faker) {
    return [
        'user_id' => ThxUser::class,
        'tdc' => $faker->creditCardNumber,
        'amount' => 9000,
        'bill_day' => $faker->dayOfMonth,
        'file_name' => 'fake-file-name',
        'paycyps_id' => '12_' . $faker->randomNumber(3),
    ];
});
