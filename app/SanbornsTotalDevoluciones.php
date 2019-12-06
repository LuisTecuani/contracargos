<?php

namespace App;

use App\SanbornsTotalCobros;
use Illuminate\Database\Eloquent\Model;

class SanbornsTotalDevoluciones extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'veces_devuelto',
        'total_devoluciones'
    ];

    protected $table = 'sanborns_total_devoluciones';
}
