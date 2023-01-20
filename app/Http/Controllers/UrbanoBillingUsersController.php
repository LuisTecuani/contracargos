<?php

namespace App\Http\Controllers;

use App\UrbanoBillingUsers;
use App\UrbanoBlacklist;
use App\Repsurbano;
use App\RespuestasBanorteUrbano;
use App\UserTdcUrbano;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrbanoBillingUsersController extends Controller
{
    public function index()
    {
        $billUsers = count($this->billUsers());

        return view('urbano/billing_users/index', compact('billUsers'));
    }

    public function storeFtp(Request $request)
    {
        $procedence = $request->procedence;

        $file = $request->file('file');

        $rows = preg_grep("/(844475030)/", file($file));


        foreach ($rows as $row) {
            $id = substr($row, 9, 6);

            $data = UserTdcUrbano::select("exp_month", "exp_year", "number")
                ->where('user_id', '=', $id)
                ->latest()
                ->first();


            if (is_numeric($data->exp_year) && strlen($data->exp_year) >= 3) {
                $date = DateTime::createFromFormat('Y-m', $data->exp_year
                    . '-' . $data->exp_month)
                    ->format('y-m');
            } else {
                $date = 1111;
            }
            UrbanoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => 1
            ]);
        }
        $billUsers = count($this->billUsers());
        return view('urbano/billing_users/index', compact('billUsers'));
    }

    public function storeRejectedProsa(Request $request)
    {
        $procedence = $request->procedence;

        //select last four dates
        $dates = RespuestasBanorteUrbano::select('fecha')->groupBy('fecha')->orderBy('fecha', 'desc')->limit(4)->get();
        $banorte = (new RespuestasBanorteUrbano)->getNotBillables($dates);

        $prosa = (new Repsurbano)->getNotBillables($dates);

        $noMore = Repsurbano::select('user_id as id')
            ->where('fecha', '=', $dates[3]->fecha)
            ->get();

        $users = RespuestasBanorteUrbano::select('user_id')
            ->where('fecha', '=', $dates[0]->fecha)
            ->whereIn('detalle_mensaje', ['Excede intentos de NIP','Ingrese un monto menor','Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->whereNotIn('user_id', $banorte)
            ->whereNotIn('user_id', $prosa)
            ->whereNotIn('user_id', $noMore)
            ->get();

        foreach ($users as $user) {

            $data = UserTdcUrbano::select("exp_month", "exp_year", "number")

                ->where('user_id', '=', $user->user_id)
                ->latest()
                ->first();

            if (is_numeric($data->exp_year) && strlen($data->exp_year) >= 3) {
                $date = DateTime::createFromFormat('Y-m', $data->exp_year
                    . '-' . $data->exp_month)
                    ->format('y-m');
            } else {
                $date = 1111;
            }
            UrbanoBillingUsers::create([
                'user_id' => $user->user_id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $billUsers = count($this->billUsers());
        return view('urbano/billing_users/index', compact('billUsers'));
    }

    public function storeToBanorte(Request $request)
    {
        $procedence = $request->procedence;

        $date = Repsurbano::select('fecha')->orderBy('fecha', 'desc')->first()->fecha;

        $query = UrbanoBlacklist::select('user_id')->whereNotNull('user_id');

        $users = Repsurbano::select('user_id as id')
            ->where('fecha', 'like', $date)
            ->whereIn('detalle_mensaje', ['Excede limite de disposiciones diarias','Excede intentos de NIP','Fondos insuficientes', 'Supera el monto límite permitido', 'Límite diario excedido', 'Imposible autorizar en este momento'])
            ->whereNotIn('user_id', $query)
            ->get();

        foreach ($users as $user) {

            $data = UserTdcUrbano::select("exp_date", "number")
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

            UrbanoBillingUsers::create([
                'user_id' => $user->id,

                'procedence' => $procedence,

                'exp_date' => $date,

                'number' => $data->number
            ]);
        }
        $billUsers = count($this->billUsers());
        return view('urbano/billing_users/index', compact('billUsers'));
    }

    public function storeTextbox(Request $request)
    {
        $procedence = $request->procedence;
        $ids = preg_split("[\r\n]", $request->ids);

        foreach ($ids as $id) {
            $data = UserTdcUrbano::select("number")
                ->where('user_id', 'like', $id)
                ->whereDefault('1')
                ->latest()
                ->first();

            if (! $data) {
                $data = collect();
                $data->number = '1';
            }

            if( Str::contains($data->number, '*') or !$data->number) {
                $data->number = '1';
            }

            UrbanoBillingUsers::create([
                'user_id' => $id,

                'procedence' => $procedence,

                'exp_date' => 1111,

                'number' => $data->number ?? '1'

            ]);
        }
        $billUsers = count($this->billUsers());
        return view('urbano/billing_users/index', compact('billUsers'));
    }



    public function billUsers()
    {
        return UrbanoBillingUsers::select('user_id')
            ->where([
                ['created_at', 'like', now()->format('Y-m-d') . '%']])
            ->get();
    }
}
