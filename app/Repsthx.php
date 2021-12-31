<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Repsthx extends Model
{
    protected $guarded = [];

    protected $table = 'repsthx';

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
                'Excede limite de disposiciones diarias'
            ])
            ->get();
    }

    public function getBinAttribute()
    {
        return Str::substr($this->tarjetas, 0, 6);
    }

    public function bin()
    {
        return $this->belongsTo(Bin::class, 'bin', 'bin');
    }
}
