<?php

namespace App\Http\Controllers;

use App\AliadoBillingUsers;
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
            AliadoBillingUsers::create([
                'user_id' => $id,
                'procedence' => $procedence,
                'exp_date' => DateTime::createFromFormat('my', $dates->exp_month . substr($dates->exp_year, -2))
                    ->format("y-m"),
            ]);
        }
        $users = count($rows);
        return view('/aliado/banorte/results', compact('users'));
    }
}
