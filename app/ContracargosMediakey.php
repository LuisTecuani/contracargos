<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContracargosMediakey extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_mediakey';

    protected $fillable = ['autorizacion',
        'tarjeta'
    ];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
