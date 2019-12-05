<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestasBanorteCellers extends Model
{
    protected $guarded = [];

    protected $table = 'respuestas_banorte_cellers';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
