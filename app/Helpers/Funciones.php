<?php


    /**
     * Reindexa un array o un array de arrays
     * */

if (!function_exists('fixKeys')) {
    function fixKeys($array)
    {
        foreach ($array as $k => $val) {
            if (is_array($val))
                $array[$k] = fixKeys($val); //recursive
        }
        return array_values($array);
    }

    function accepRepToArray($texto)
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
}

