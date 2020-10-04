<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repscellers extends Model
{
    /**
     * Specify the connection, since this implements multitenant solution
     * Called via constructor to faciliate testing
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('database.default'));
    }

    protected $guarded = [];

    protected $table = 'repscellers';

    /**
     * Get not billable users.
     */
    public function getNotBillables($dates)
    {
        return $this->select('user_id as id')
            ->where('fecha', '>=', $dates[3]->fecha)
            ->whereNotIn('detalle_mensaje', [
                'Excede intentos de NIP',
                'Ingrese un monto menor',
                'Fondos insuficientes',
                'Supera el monto límite permitido',
                'Límite diario excedido',
                'Imposible autorizar en este momento',
                'Excede limite de disposicion diaria',
                'Excede limite de disposiciones diarias',
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
