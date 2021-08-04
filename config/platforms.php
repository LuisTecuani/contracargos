<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platfom data
    |--------------------------------------------------------------------------
    |
    */

    'cellers' => [
        'name' => 'Cellers',
        'card_model' => 'App\UserTdcCellers',
        'affinitas' => '7547998', //pensar nombre
        'respuestas_banorte_model' => 'App\RespuestasBanorteCellers',
        'billing_users_model' => 'App\CellersBillingUsers',
    ],

    'thx' => [
        'name' => 'Thx',
        'card_model' => 'App\UserTdcThx',
        'affinitas' => '7816884', //pensar nombre
        'respuestas_banorte_model' => 'App\RespuestasBanorteThx',
        'billing_users_model' => 'App\ThxBillingUsers',
    ],

    'urbano' => [
        'name' => 'Urbano',
        'card_model' => 'App\UserTdcUrbano',
        'affinitas' => '8444750',
        'respuestas_banorte_model' => 'App\RespuestasBanorteUrbano',
        'billing_users_model' => 'App\UrbanoBillingUsers',
    ],
];
