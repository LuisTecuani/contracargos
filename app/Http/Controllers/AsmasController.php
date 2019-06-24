<?php

namespace App\Http\Controllers;

use App\Repsasmas;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsmasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index() {


        return view('asmas.index', compact('cards'));
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

            $cards = DB::table('consultas.repsasmas as ra')
                ->leftjoin('asmas.users as u', 'u.id', '=', 'ra.user_id')
                ->leftjoin('asmas.user_tdc as ut', 'u.id', '=', 'ut.user_id')
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
            }


        }


        return view('asmas.index')->with(compact('cards'));



    }

    public function import(Request $request)
    {

        $archivos    =   $request->file('files');

        foreach($archivos as $file)
        {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.repsasmas')
                ->where( 'source_file', 'like', $source)->get();

            if (count($valid) === 0)
            {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
                {
                    $rep4 = accepRepToArray($rep10);

                    foreach ($rep4 as $rep3)
                    {


                        Repsasmas::create([

                            'tarjeta' => $rep3[0],

                            'user_id' => $rep3[1],

                            'fecha' => $rep3[2],

                            'terminacion' => substr($rep3[0],-4,4),

                            'autorizacion' => $rep3[5],

                            'monto' => $rep3[8],

                            'source_file' => $source

                        ]);
                    }
                }
            }
        }

        return back();

    }

}
