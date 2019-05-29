<?php

namespace App\Http\Controllers;

use App\Repsaliado;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AliadoController extends Controller
{
    public function index() {


        return view('aliado.index', compact('cards'));
    }


    public function finder() {

        $autorizacionesS =  request()->input('autorizaciones');

        $autorizacionesRaw = preg_split("[\r\n]",$autorizacionesS);

        echo '"num_buscado","autorizacion","fecha","number","user_id","email"'."<br>";

        foreach($autorizacionesRaw as $autorizacionRaw1) {

            $autorizacionRaw = preg_split("[,]",$autorizacionRaw1);

            if(strlen($autorizacionRaw[0]) == 1)
            {
                $autorizacion = "00000$autorizacionRaw[0]";
            }

            if(strlen($autorizacionRaw[0]) == 2)
            {
                $autorizacion = "0000$autorizacionRaw[0]";
            }

            if(strlen($autorizacionRaw[0]) == 3)
            {
                $autorizacion = "000$autorizacionRaw[0]";
            }

            if(strlen($autorizacionRaw[0]) == 4)
            {
                $autorizacion = "00$autorizacionRaw[0]";
            }

            if(strlen($autorizacionRaw[0]) == 5)
            {
                $autorizacion = "0$autorizacionRaw[0]";
            }

            if(strlen($autorizacionRaw[0]) == 6)
            {
                $autorizacion = "$autorizacionRaw[0]";
            }

            $tarjeta = "%$autorizacionRaw[1]";

            $cards = DB::table('consultas.repsaliado as ra')
                ->leftjoin('aliado.users as u', 'u.id', '=', 'ra.user_id')
                ->leftjoin('aliado.user_tdc as ut', 'u.id', '=', 'ut.user_id')
                ->where([
                    ['ra.autorizacion', '=', $autorizacion],
                    ['ut.number', 'like', $tarjeta]
                ])->get();

            foreach($cards as $ca)
            {

                echo $autorizacion.',"';
                echo $ca->autorizacion.'","';
                echo $ca->fecha.'","';
                echo $ca->number.'",';
                echo $ca->id.',';
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

                    Repsaliado::create([

                        'tarjeta' => $rep3[0],

                        'user_id' => $rep3[1],

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
