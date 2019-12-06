<?php

namespace App;

use App\SanbornsTotalDevoluciones;
use Illuminate\Database\Eloquent\Model;

class SanbornsTotalCobros extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'veces_cobrado',
        'total_cobros'
    ];

    protected $table = 'sanborns_total_cobros';

}
