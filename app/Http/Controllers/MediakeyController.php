<?php

namespace App\Http\Controllers;

use App\CreditCards;
use App\Repsmediakey;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\DB;
use App\Providers\BroadcastServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;


class MediakeyController extends Controller
{

    public function index() {

        if ($searched_card = [request('tarjetas')]) {


        $cards = CreditCards::with('user')->where('number', 'like', compact('searched_card'))->get();


        } else {
            $cards = CreditCards::with('user')->latest()->paginate(14);
        }

        return view('mediakey.index', compact('cards'));
    }

    public function show()
    {

        $cardsA = [
            '415231%0840',
            '549949%8516',
            '549949%8516',
            '549949%8516',
            '415231%1570',
            '415231%7748',
            '547046%4570',
            '493172%3614',
            '528843%3700',
            '426807%6665',
            '426807%6665',
            '415231%0667',
            '415231%4658',
            '415231%1875',
            '491573%3115',
            '415231%1570',
            '415231%1570'
        ];


        echo '"id","email","number","num_buscado"'."<br>";
        foreach($cardsA as $card) {

            $cards = CreditCards::with('user')->where('number', 'like', compact('card'))->get();
            foreach($cards as $ca){
                echo $ca->user_id.',"';
                echo $ca->user->email.'",';
                echo $ca->number.','.$card."<br>";
        }}
        return (new UsersExport)->download('users.xlsx');
    }

    public function finder() {


            $autorizacionesS =  request()->input('autorizaciones');

            $autorizacionesRaw = preg_split("[\r\n]",$autorizacionesS);

            echo '"num_buscado","autorizacion","fecha","number","user_id","email"'."<br>";

            foreach($autorizacionesRaw as $autorizacionRaw) {
                if(strlen($autorizacionRaw) == 1)
                {
                    $autorizacion = "00000$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 2)
                {
                    $autorizacion = "0000$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 3)
                {
                    $autorizacion = "000$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 4)
                {
                    $autorizacion = "00$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 5)
                {
                    $autorizacion = "0$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 6)
                {
                    $autorizacion = "$autorizacionRaw";
                }

                $cards = DB::table('consultas.repsmediakey as rm')
                    ->leftjoin('mediakey.users as u', 'u.id', '=', 'rm.user_id')
                    ->leftjoin('mediakey.credit_cards as cc', 'u.id', '=', 'cc.user_id')
                    ->where('rm.autorizacion', '=', $autorizacion)
                    ->get();

                foreach($cards as $ca)
                {

                    echo $autorizacion.',"';
                    echo $ca->autorizacion.'","';
                    echo $ca->fecha.'","';
                    echo $ca->number.'",';
                    echo $ca->id.',';
                    echo $ca->email."<br>";
                }}


            return view('mediakey.index')->with(compact('cards'));



        }

    public function import()
    {
        function fix_keys($array) {
            foreach ($array as $k => $val) {
                if (is_array($val))
                    $array[$k] = fix_keys($val); //recurse
            }
            return array_values($array);
        }



        $rep10 = file_get_contents(request()->file('file')) ;
        if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
        {
            $rep9 = Str::after($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS                        ');
            $rep8 = Str::before($rep9, 'Totales:                                                                           ');
            $rep7 = Arr::sort(preg_split("/\n/", $rep8));
            $rep6 = preg_grep("/([[:digit:]]{16})/", $rep7);

            foreach ($rep6 as $cls => $vls) {
                $rep5[$cls] = preg_grep("/\S/", preg_split("/\s/", $vls));
            }

            $rep4 = fix_keys($rep5);

            foreach ($rep4 as $rep3) {
                $rep3[10] = Str::after($rep3[10], 'C0000000');

                Repsmediakey::create([

                    'tarjeta' => $rep3[0],

                    'user_id' => $rep3[10],

                    'fecha' => $rep3[2],

                    'autorizacion' => $rep3[5],

                    'monto' => $rep3[8]

                ]);

            }


            return back();
        } else
        {
            return view('noaceptados');
        }

    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}


