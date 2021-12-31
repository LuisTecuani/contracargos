<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platfom data
    |--------------------------------------------------------------------------
    |
    */

    'cellers' => [
        'name' => 'cellers',
        'card_model' => 'App\UserTdcCellers',
        'affinitas' => '7547998', //pensar nombre
        'respuestas_banorte_model' => 'App\RespuestasBanorteCellers',
        'reps_model' => 'App\Repscellers',
        'billing_users_model' => 'App\CellersBillingUsers',
        'user_model' => 'App\CellersUser',
    ],

    'thx' => [
        'name' => 'thx',
        'card_model' => 'App\UserTdcThx',
        'reps_model' => 'App\Repsthx',
        'affinitas' => '7816884', //pensar nombre
        'respuestas_banorte_model' => 'App\RespuestasBanorteThx',
        'billing_users_model' => 'App\ThxBillingUsers',
        'user_model' => 'App\ThxUser',
    ],

    'urbano' => [
        'name' => 'urbano',
        'card_model' => 'App\UserTdcUrbano',
        'affinitas' => '8444750',
        'reps_model' => 'App\Repsurbano',
        'respuestas_banorte_model' => 'App\RespuestasBanorteUrbano',
        'billing_users_model' => 'App\UrbanoBillingUsers',
        'user_model' => 'App\UrbanoUser',
    ],

    'urbano2' => [
        'name' => 'urbano',
        'card_model' => 'App\UserTdcUrbano',
        'affinitas' => '8010897',
        'reps_model' => 'App\Repsurbano',
        'respuestas_banorte_model' => 'App\RespuestasBanorteUrbano',
        'billing_users_model' => 'App\UrbanoBillingUsers',
        'user_model' => 'App\UrbanoUser',
    ],

    'urbano3' => [
        'name' => 'urbano',
        'card_model' => 'App\UserTdcUrbano',
        'affinitas' => '7823918',
        'reps_model' => 'App\Repsurbano',
        'respuestas_banorte_model' => 'App\RespuestasBanorteUrbano',
        'billing_users_model' => 'App\UrbanoBillingUsers',
        'user_model' => 'App\UrbanoUser',
    ],
];
