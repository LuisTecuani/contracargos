<?php

namespace App\Http\Controllers;

use App\CellersBillingUsers;
use App\Repscellers;
use App\RespuestasBanorteCellers;
use App\UserTdcCellers;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CellersBillingUsersController extends Controller
{
    public function index()
    {
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('cellers/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeFtp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(809295030)/", file($file));;

        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $data = UserTdcCellers::select("exp_date", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_date) && strlen($data->exp_date)>= 3) {
                $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                    . '-' . substr($data->exp_date, 0, -2))
                    ->format('y-m');
            } else {
                $date = 1111;
            }

            CellersBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }

        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/cellers/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeRejectedProsa(Request $request)
    {
        $procedence = $request->procedence;

        $dates = RespuestasBanorteCellers::select('fecha')->groupBy('fecha')->orderBy('fecha', 'desc')->limit(4)->get();

        foreach ($dates as $row=>$data)
        {
            if($row < 3) {
                $query = RespuestasBanorteCellers::select('user_id')
                    ->where('fecha', '=', $dates[$row + 1]->fecha);

                $query2 = Repscellers::select('user_id')
                    ->where([['fecha', '>=', $dates[3]], ['estatus', 'like', 'Aprobada']]);

                $users = RespuestasBanorteCellers::select('user_id')
                    ->where([['fecha', '=', $data->fecha], ['estatus', 'not like', 'Aprobada']])
                    ->whereNotIn('user_id', $query)
                    ->whereNotIn('user_id', $query2)
                    ->get();

                foreach ($users as $user) {

                    $data = UserTdcCellers::select("exp_date", "number")
                        ->where('user_id', '=', $user->user_id)
                        ->latest()
                        ->first();

                    if (is_numeric($data->exp_date) && strlen($data->exp_date)>= 3) {
                        $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                            . '-' . substr($data->exp_date, 0, -2))
                            ->format('y-m');
                    } else {
                        $date = 1111;
                    }

                    CellersBillingUsers::create([
                        'user_id' => $user->user_id,

                        'procedence' => $procedence,

                        'exp_date' => $date,

                        'number' => $data->number
                    ]);


                }
            }
        }


        $expUsers = count($this->expDates());

        $vigUsers = count($this->vigDates());

        return view('/cellers/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeRejectedBanorte(Request $request)
    {
        $procedence = $request->procedence;

        $users = RespuestasBanorteCellers::select('user_id as id')
            ->whereIn('detalle_mensaje', ['Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->where(
                'fecha', 'like', $request->date
            )->get();

        foreach ($users as $user) {

            $data = UserTdcCellers::select("exp_date", "number")
                ->where('user_id', '=', $user->id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_date) && strlen($data->exp_date)>= 3) {
                $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                    . '-' . substr($data->exp_date, 0, -2))
                    ->format('y-m');
            } else {
                $date = 1111;
            }

            CellersBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/cellers/billing_users/index', compact('expUsers', 'vigUsers'));
    }

    public function storeTextbox(Request $request)
    {
        $procedence = $request->procedence;

        $ids = preg_split("[\r\n]", $request->ids);

        foreach ($ids as $id) {

            $data = UserTdcCellers::select("exp_date", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_date) && strlen($data->exp_date)>= 3) {
                $date = DateTime::createFromFormat('y-m', substr($data->exp_date, -2, 2)
                    . '-' . substr($data->exp_date, 0, -2))
                    ->format('y-m');
            } else {
                $date = 1111;
            }

            CellersBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }

        $expUsers = count($this->expDates());
        $vigUsers = count($this->vigDates());

        return view('/cellers/billing_users/index', compact('expUsers', 'vigUsers'));
    }



    public function expDates()
    {
        return CellersBillingUsers::select('user_id')
            ->where([
                ['exp_date', '<', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }

    public function vigDates()
    {
        return CellersBillingUsers::select('user_id')
            ->where([
                ['exp_date', '>=', now()->format('y-m')],
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }


}
