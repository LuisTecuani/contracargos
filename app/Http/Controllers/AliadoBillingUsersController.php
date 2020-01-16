<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use DateTime;
use Illuminate\Http\Request;

class AliadoBillingUsersController extends Controller
{
    public function index()
    {
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('aliado/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeFtp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(801089727)/", file($file));;

        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }

        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/billing_users/index', compact('expUsers', 'vigUsers'));
    }


    public function storeRejectedProsa(Request $request)
    {
        $procedence = $request->procedence;

        $date = $request->date;

        $users = Repsaliado::select('user_id as id')
            ->whereIn('motivo_rechazo', ['Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->where(
                'fecha', 'like', $date
            )->get();

        foreach ($users as $user) {

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $user->id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeRejectedBanorte(Request $request)
    {
        $procedence = $request->procedence;

        $date = $request->date;

        $users = RespuestasBanorteAliado::select('user_id as id')
            ->whereIn('detalle_mensaje', ['Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->where(
                'fecha', 'like', $date
            )->get();

        foreach ($users as $user) {

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $user->id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeTextbox(Request $request)
    {
        $procedence = $request->procedence;

        $ids = preg_split("[\r\n]", $request->ids);

        foreach ($ids as $id) {

            $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            $d = $data->exp_month . substr($data->exp_year, -2);

            AliadoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                    . '-' . substr($d, 0, -2))->format('y-m'),

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/aliado/billing_users/index', compact('expUsers', 'vigUsers'));
    }



    public function expDates()
    {
        return AliadoBillingUsers::select('user_id')
            ->where([
                ['exp_date', '<', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }

    public function vigDates()
    {
        return AliadoBillingUsers::select('user_id')
            ->where([
                ['exp_date', '>=', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }


}
