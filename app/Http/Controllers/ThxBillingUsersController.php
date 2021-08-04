<?php

namespace App\Http\Controllers;

use App\Repsthx;
use App\RespuestasBanorteThx;
use App\ThxBillingUsers;
use App\UserTdcThx;
use DateTime;
use Illuminate\Http\Request;

class ThxBillingUsersController extends Controller
{
    public function index()
    {
        $billUsers = count($this->billUsers());

        return view('thx/billing_users/index', compact('billUsers'));
    }

    public function storeFtp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(781688430)/", file($file));

        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $data = UserTdcThx::select("exp_date", "number")
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
            ThxBillingUsers::create([
                'user_id' => $id,
                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $billUsers = count($this->billUsers());
        return view('thx/billing_users/index', compact('billUsers'));
    }

    public function storeRejectedProsa(Request $request)
    {
        $procedence = $request->procedence;

        //select last four dates
        $dates = RespuestasBanorteThx::select('fecha')->groupBy('fecha')->orderBy('fecha', 'desc')->limit(4)->get();

        $banorte = (new RespuestasBanorteThx)->getNotBillables($dates);

        $prosa = (new Repsthx)->getNotBillables($dates);

        $noMore = Repsthx::select('user_id as id')
            ->where('fecha', '=', $dates[3]->fecha)
            ->get();

        $users = RespuestasBanorteThx::select('user_id')
            ->where('fecha', '=', $dates[0]->fecha)
            ->whereIn('detalle_mensaje', ['Excede intentos de NIP','Ingrese un monto menor','Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->whereNotIn('user_id', $banorte)
            ->whereNotIn('user_id', $prosa)
            ->whereNotIn('user_id', $noMore)
            ->get();

        foreach ($users as $user) {

            $data = UserTdcThx::select("exp_date", "number")
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

            ThxBillingUsers::create([
                'user_id' => $user->user_id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $billUsers = count($this->billUsers());
        return view('thx/billing_users/index', compact('billUsers'));
    }

    public function storeTextbox(Request $request)
    {
        $procedence = $request->procedence;

        $ids = preg_split("[\r\n]", $request->ids);

        foreach ($ids as $id) {

            $data = UserTdcThx::select("exp_date", "number")
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

            ThxBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }

        $billUsers = count($this->billUsers());

        return view('/thx/billing_users/index', compact('billUsers'));
    }

    public function billUsers()
    {
        return ThxBillingUsers::select('user_id')
            ->where([
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }
}
