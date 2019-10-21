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
}

function processRep($file)
{
    $rows = preg_grep("/([[:digit:]]{16})/",file($file));
    $rejected = [];
    $accepted = [];
    foreach ($rows as $row => $cont) {
        if (Str::contains($cont, ['B036', 'P128'])) {
            $accepted[$row] = preg_grep("/\S/", preg_split("/\s/", $cont));
        }else{
            $rejected[$row] = fixKeys(preg_grep("/\S/", preg_split("/\s/", $cont)));
            $rejected[$row]['motivo'] = substr($cont, 60, 50);
        //
        }
    }

    return $rejected;
}

function processXml($file)
{
    $dataRaw = preg_grep("(Cargo afiliacion)",preg_split("[>]",file_get_contents($file)));
    $data = [];
    foreach ($dataRaw as $index => $content)
    {
        $data[$index] = preg_split( '(" |"\/)', $content);
        $r = [];
        foreach ($data[$index] as $ls => $vs)
        {
            $cut = preg_split( '(=)', $vs);
            $r[$cut[0]] = isset($cut[1]) ? Str::after($cut[1], '"') : null;
        }

        $data[$index] = $r;

    }
    return $data;
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


