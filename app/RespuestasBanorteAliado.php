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

    /**
     * Get not billable users.
     */
    public function getNotBillables($dates)
    {
        return $this->select('user_id as id')
            ->where('fecha', '>=', $dates[3]->fecha)
            ->whereNotIn('detalle_mensaje', [
                'Ingrese un monto menor',
                'Fondos insuficientes',
                'Supera el monto límite permitido',
                'Límite diario excedido',
                'Imposible autorizar en este momento',
                'Excede limite de disposicion diaria'
            ])
            ->get();
    }

    public function getUserAcceptedCharges($id)
    {
        return $this->select('fecha', 'tarjeta')
            ->where([['user_id','=',$id],['estatus','=','Aprobada']])
            ->get();
    }
}
