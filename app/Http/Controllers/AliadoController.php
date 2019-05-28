<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repsaliado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class AliadoController extends Controller
{
    public function index() {


        return view('aliado.index', compact('cards'));
    }


    public function finder() {

        $autorizacionesS =  request()->input('autorizaciones');

        $autorizacionesRaw = preg_split("[\r\n]",$autorizacionesS);

        echo '"num_buscado","autorizacion","fecha","number","email"'."<br>";

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


            $cards = DB::connection('mysql3')->table('reps')
                ->leftJoin('user_tdc', 'reps.numero', '=', 'user_tdc.number')
                ->leftJoin('users', 'user_tdc.user_id', '=', 'users.id')
                ->where('reps.autorizacion', '=', $autorizacion)
                ->get();

            foreach($cards as $ca)
            {
                echo $autorizacion.',"';
                echo $ca->autorizacion.'","';
                echo $ca->fecha_cobro.'","';
                echo $ca->number.'",';
                echo $ca->email."<br>";
            }}


        return view('aliado.index')->with(compact('cards'));



    }

    public function import(Request $request)
    {
        function fix_keys($array) {
            foreach ($array as $k => $val) {
                if (is_array($val))
                    $array[$k] = fix_keys($val); //recurse
            }
            return array_values($array);
        }




        foreach($request->file('files') as $file)
        {


            $rep10 = file_get_contents($file);
            if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS')) {
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

                    Repsaliado::create([

                        'tarjeta' => $rep3[0],

                        'user_id' => $rep3[10],

                        'fecha' => $rep3[2],

                        'autorizacion' => $rep3[5],

                        'monto' => $rep3[8]

                    ]);

                }


            }
        }
return back();
    }
}
