<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repsmediakey extends Model
{

    protected $guarded = [];

    protected $table = 'repsmediakey';

    public function getUserAcceptedCharges($id)
    {
        return $this->select('fecha', 'tarjeta')
            ->where([['user_id','=',$id],['estatus','=','Aprobada']])
            ->get();
    }
}
