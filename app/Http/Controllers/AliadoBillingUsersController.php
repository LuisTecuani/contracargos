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

        $date = Repsaliado::select('fecha')->groupBy('fecha')->orderBy('fecha', 'desc')->skip(3)
            ->first()->fecha;

        $aReps = Repsaliado::select('user_id')->where([['fecha','>=',$date],['estatus','=','Aprobada']])->get();

        $aBano = RespuestasBanorteAliado::select('user_id')->where([['fecha','>=',$date],['estatus','=','Aprobada']])->get();

        $sub = Repsaliado::selectRaw('user_id, count(*) as c')
            ->where([['fecha','>=',$date],['source_file','like','%3918']])
            ->groupBy('user_id');

        $users = DB::table( DB::raw("({$sub->toSql()}) as sub") )
            ->mergeBindings($sub->getQuery())
            ->select('user_id')
            ->whereNotIn('user_id', $aReps)
            ->whereNotIn('user_id', $aBano)
            ->where('c', '<', 4)
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

        return back();
    }

    public function storeToBanorte(Request $request)
    {
        $procedence = $request->procedence;

        $users = Repsaliado::select('user_id as id')
            ->where([['estatus', 'not like','Aprobada'],['fecha', 'like', date("Y-m-d")],['source_file', 'like', '%0897']])
            ->get();

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

        $users = RespuestasBanorteAliado::select('user_id as id')
            ->where([['estatus', 'not like','Aprobada'],['fecha', 'like', date("Y-m-d")]])
            ->get();

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
