<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContracargosAliadoBanorte extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_aliado_banorte';

    protected $fillable = ['autorizacion', 'fecha_consumo',
        'tarjeta', 'user_id', 'email', 'fecha_rep'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
