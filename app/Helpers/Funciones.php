<?php


    /**
     * Reindexa un array o un array de arrays
     * */

if (!function_exists('fix_keys')) {
    function fix_keys($array)
    {
        foreach ($array as $k => $val) {
            if (is_array($val))
                $array[$k] = fix_keys($val); //recursive
        }
        return array_values($array);
    }
}


    /**
     * De los .rep toma los datos de los usuarios aceptados
     * y los prepara como array
     * */

    function accep_rep_to_array($texto)
    {

        $rep9 = Str::after($texto, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS                        ');
        $rep8 = Str::before($rep9, 'Totales:                                                                           ');
        $rep7 = Arr::sort(preg_split("/\n/", $rep8));
        $rep6 = preg_grep("/([[:digit:]]{16})/", $rep7);
        $rep5 = [];

        foreach ($rep6 as $cls => $vls) {

            $rep5[$cls] = preg_grep("/\S/", preg_split("/\s/", $vls));

        }

        $rep4 = fix_keys($rep5);
        return $rep4;
    }



