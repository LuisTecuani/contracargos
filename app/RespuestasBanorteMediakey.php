<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestasBanorteMediakey extends Model
{
    protected $guarded = [];

    protected $table = 'respuestas_banorte_mediakey';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function getUserAcceptedCharges($id)
    {
        return $this->select('fecha', 'tarjeta')
            ->where([['user_id','=',$id],['estatus','=','Aprobada']])
            ->get();
    }
}
