<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FileProcessor
{

    /**
     * De los .rep toma los datos de los usuarios aceptados
     * y los prepara como array
     * */
    public function accepRepToArray($texto)
    {

        $rep9 = Str::after($texto, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS                        ');
        $rep8 = Str::before($rep9, 'Totales:                                                                           ');
        $rep7 = Arr::sort(preg_split("/\n/", $rep8));
        $rep6 = preg_grep("/([[:digit:]]{16})/", $rep7);
        $rep5 = [];

        foreach ($rep6 as $cls => $vls) {

            $rep5[$cls] = preg_grep("/\S/", preg_split("/\s/", $vls));

        }

        $rep4 = fixKeys($rep5);
        return $rep4;
    }

    public function checkFileExistence($table, $source) {

        $valid = DB::table("consultas.$table")
            ->where( 'source_file', 'like', $source)->get();
        return $valid;
    }

    public function autorizacionSeisDigit($aut) {
        $len = strlen($aut);
        while($len < 6){
            $aut = "0$aut";
            $len = strlen($aut);
        }
        $autseisd = $aut;
        return $autseisd;
    }

}
