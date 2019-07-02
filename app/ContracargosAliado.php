<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContracargosAliado extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_aliado';

    protected $fillable = ['autorizacion',
        'tarjeta'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
