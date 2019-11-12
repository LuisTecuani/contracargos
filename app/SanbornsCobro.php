<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanbornsCobro extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'fecha',
        'importe',
        'respuesta',
        'referencia',
        'source'
    ];

    protected $table = 'sanborns';
}
