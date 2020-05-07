<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestasBanorteAliado extends Model
{
    protected $guarded = [];

    protected $table = 'respuestas_banorte_aliado';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function getRecentDates()
    {
        return $this->select('fecha')->groupBy('fecha')
            ->orderBy('fecha', 'desc')->limit(4)->get();
    }
}
