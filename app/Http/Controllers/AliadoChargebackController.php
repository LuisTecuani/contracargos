<?php

namespace App\Http\Controllers;

use App\ContracargosAliado;
use Illuminate\Http\Request;

class AliadoChargebackController extends Controller
{
    public function index()
    {

        $cards = Contracargosaliado::whereDate('created_at', today())->get();

        return view("aliado.chargeback.index", compact('cards'));
    }
/*
        DB::select('update contracargos_aliado c left join repsaliado r on r.autorizacion=c.autorizacion
            set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_aliado c join aliado.users u on u.id=c.user_id set c.email=u.email');*/
}
