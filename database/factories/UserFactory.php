<?php

use App\User;
use App\Repsaliado;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'admin', [])
    ->afterCreatingState(User::class, 'admin', function (User $user) {
        $user->roles()->save(
            factory(Role::class, 'admin')->firstOrCreate()
        );
    });

$factory->define(Repsaliado::class, function (Faker $faker) {
    $tarjeta = $faker->creditCardNumber;
    return [
        'tarjeta' => $tarjeta,
        'terminacion' => substr($tarjeta,-4,4),
        'user_id' => $faker->randomNumber(6),
        'fecha' => $faker->date('Y-m-d'),
        'autorizacion' => $faker->randomNumber(6),
        'monto' => $faker->randomNumber(4),
        'source_file' => Str::random(24),
    ];
});




