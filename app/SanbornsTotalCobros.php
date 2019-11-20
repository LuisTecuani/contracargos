<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanbornsTotalCobros extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'cuenta',
        'veces_cobros',
        'total_cobros'
    ];

    protected $table = 'sanborns_total_cobros';
}
