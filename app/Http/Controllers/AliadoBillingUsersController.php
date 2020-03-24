<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use App\UserTdcAliado;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return back();
    }


    public function storeRejectedProsa(Request $request)
    {
        $procedence = $request->procedence;

        $dates = Repsaliado::select('fecha')->where('source_file','like','%3918')->groupBy('fecha')
            ->orderBy('fecha', 'desc')->limit(4)->get();
        foreach ($dates as $row=>$data)
        {
            if($row < 3) {
                $query = Repsaliado::select('user_id')
                    ->where([['fecha', '=', $dates[$row + 1]->fecha], ['source_file', 'like', '%3918']]);

                $query2 = RespuestasBanorteAliado::select('user_id')
                    ->where([['fecha', '>=', $dates[3]], ['estatus', 'like', 'Aprobada']]);

                $users = Repsaliado::select('user_id')
                    ->where([['fecha', '=', $data->fecha], ['source_file', 'like', '%3918'], ['estatus', 'not like', 'Aprobada']])
                    ->whereNotIn('user_id', $query)
                    ->whereNotIn('user_id', $query2)
                    ->get();

                foreach ($users as $user) {

                    $data = UserTdcAliado::select("exp_month", "exp_year", "number")
                        ->where('user_id', '=', $user->user_id)
                        ->latest()
                        ->first();

                    $d = $data->exp_month . substr($data->exp_year, -2);

                    AliadoBillingUsers::create([
                        'user_id' => $user->user_id,

                        'procedence' => $procedence,

                        'exp_date' => DateTime::createFromFormat('y-m', substr($d, -2, 2)
                            . '-' . substr($d, 0, -2))->format('y-m'),

                        'number' => $data->number
                    ]);
                }
            }
        }

        return back();
    }

    public function storeToBanorte(Request $request)
    {
        $procedence = $request->procedence;

        $date = Repsaliado::select('fecha')->where('source_file','like','%0897')
            ->orderBy('fecha', 'desc')->first()->fecha;

        $users = Repsaliado::select('user_id as id')->where([['estatus', 'not like','Aprobada'],
            ['fecha', 'like', $date],['source_file', 'like', '%0897']])->get();

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

        return back();
    }

    public function storeTo3918(Request $request)
    {
        $procedence = $request->procedence;

        $date = RespuestasBanorteAliado::select('fecha')->orderBy('fecha', 'desc')->first()->fecha;

        $users = RespuestasBanorteAliado::select('user_id as id')->where([['estatus', 'not like','Aprobada'],
            ['fecha', 'like', $date]])->get();

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

        return back();
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

        return back();
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
