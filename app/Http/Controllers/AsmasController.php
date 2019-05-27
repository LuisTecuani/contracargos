<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsmasController extends Controller
{

    public function index() {


        return view('asmas.index', compact('cards'));
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


            echo $autorizacion.',"';
            $cards = DB::connection('mysql4')->table('reps_asmas')
                ->leftJoin('user_tdc', 'reps_asmas.numero', '=', 'user_tdc.number')
                ->leftJoin('users', 'user_tdc.user_id', '=', 'users.id')
                ->where('reps_asmas.autorizacion', '=', $autorizacion)
                ->get();

            foreach($cards as $ca)
            {
                echo $ca->autorizacion.'","';
                echo $ca->fecha_cobro.'","';
                echo $ca->number.'",';
                echo $ca->email;
            }
            echo "<br>";

        }


        return view('asmas.index')->with(compact('cards'));



    }

}
