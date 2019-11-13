<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanbornsDevolucion extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'fecha',
        'importe',
        'referencia',
        'source'
    ];

    protected $table = 'sanborns_devoluciones';
}
