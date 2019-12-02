<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanbornsDevolucionCobro extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'fecha',
        'importe',
        'respuesta',
        'referencia',
        'source',
        'tipo'
    ];

    protected $table = 'sanborns_devoluciones_cobros';
}
