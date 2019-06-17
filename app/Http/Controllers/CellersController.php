<?php

namespace App\Http\Controllers;

use App\Repscellers;
use App\CreditCards;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CellersController extends Controller
{
    public function index() {

        if ($searched_card = [request('tarjetas')]) {


            $cards = CreditCards::with('user')->where('number', 'like', compact('searched_card'))->get();


        } else {
            $cards = CreditCards::with('user')->latest()->paginate(14);
        }

        return view('cellers.index', compact('cards'));
    }

    public function finder() {

        $autorizacionesS =  request()->input('autorizaciones');

        $autorizacionesRaw = preg_split("[\r\n]",$autorizacionesS);

        echo '"num_buscado","autorizacion","fecha","number","user_id","email"'."<br>";
        foreach($autorizacionesRaw as $autorizacionRaw1) {

            $autorizacionRaw = preg_split("[,]", $autorizacionRaw1);


            if (strlen($autorizacionRaw[0]) == 1) {
                $autorizacion = "00000$autorizacionRaw[0]";
            }

            if (strlen($autorizacionRaw[0]) == 2) {
                $autorizacion = "0000$autorizacionRaw[0]";
            }

            if (strlen($autorizacionRaw[0]) == 3) {
                $autorizacion = "000$autorizacionRaw[0]";
            }

            if (strlen($autorizacionRaw[0]) == 4) {
                $autorizacion = "00$autorizacionRaw[0]";
            }

            if (strlen($autorizacionRaw[0]) == 5) {
                $autorizacion = "0$autorizacionRaw[0]";
            }

            if (strlen($autorizacionRaw[0]) == 6) {
                $autorizacion = "$autorizacionRaw[0]";
            }

            $tarjeta = "%$autorizacionRaw[1]";

            $cards = DB::table('consultas.repscellers as rc')
                ->leftjoin('cellers.users as u', 'u.id', '=', 'rc.user_id')
                ->leftjoin('cellers.tdc as t', 'u.id', '=', 't.user_id')
                ->where([
                    ['rc.autorizacion', '=', $autorizacion],
                    ['t.number', 'like', $tarjeta]
                ])->get();


            echo $autorizacion . ',"';

            if (count($cards) === 0) {
                echo 'usuario no encontrado","???","???","???"';
            } else {
                foreach ($cards as $ca) {


                    echo $ca->autorizacion . '","';
                    echo $ca->fecha . '","';
                    echo $ca->number . '",';
                    echo $ca->user_id . ',';
                    echo $ca->email;
                }

            }
            echo "<br>";
        }
        return view('cellers.index')->with(compact('cards'));

    }


    public function import(Request $request)
    {

        $archivos     =   $request->file('files');

        foreach($archivos as $file)
        {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.repscellers as rc')
                ->where( 'source_file', 'like', $source)->get();

            if (count($valid) === 0)
            {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
                {
                    $rep4 = accep_rep_to_array($rep10);

                    foreach ($rep4 as $rep3)
                    {


                        Repscellers::create([

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
