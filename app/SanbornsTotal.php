<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanbornsTotal extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'veces_cobros',
        'total_cobros',
        'veces_devuelto',
        'total_devoluciones'
    ];

    protected $table = 'sanborns_totales';
}
