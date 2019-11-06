<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestaBanorteAliado extends Model
{
    protected $guarded = [];

    protected $table = 'respuestas_banorte_aliado';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
