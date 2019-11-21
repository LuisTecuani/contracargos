<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
use App\RespuestaBanorteAliado;
use App\UserTdcAliado;
use DateTime;
use Illuminate\Http\Request;

class AliadoBanorteController extends Controller
{
    public function index()
    {
        return view('aliado.banorte.index');
    }

    public function ftp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(801089727)/", file($file));;

        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $dates = UserTdcAliado::select("exp_month", "exp_year")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            $d = $dates->exp_month . substr($dates->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => substr($d, -2, 2).'-'.substr($d, 0, -2),
            ]);
        }

        $users = count($rows);

        return view('/aliado/banorte/results', compact('users'));
    }

    public function billingRejected(Request $request)
    {
        $procedence = $request->procedence;

        $date = $request->date;

        $users = RespuestaBanorteAliado::select('user_id as id')
            ->whereIn('detalle_mensaje', ['Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->where(
                'fecha', 'like', $date
            )->get();

        foreach ($users as $user) {

            $dates = UserTdcAliado::select("exp_month", "exp_year")
                ->where('user_id', '=', $user->id)
                ->latest()
                ->first();

            $d = $dates->exp_month . substr($dates->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => substr($d, -2, 2).'-'.substr($d, 0, -2),
            ]);
        }
        $users = count($users);

        return view('/aliado/banorte/results', compact('users'));
    }

    public function usersTextbox(Request $request)
    {
        $procedence = $request->procedence;

        $ids = preg_split("[\r\n]",$request->ids);

        foreach ($ids as $id) {

            $dates = UserTdcAliado::select("exp_month", "exp_year")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            $d = $dates->exp_month . substr($dates->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => substr($d, -2, 2).'-'.substr($d, 0, -2),
            ]);
        }
        $users = count($ids);

        return view('/aliado/banorte/results', compact('users'));
    }
}
