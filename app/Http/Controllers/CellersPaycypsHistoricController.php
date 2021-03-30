<?php

namespace App\Http\Controllers;

use App\CellersPaycypsHistoric;
use App\Imports\CellersPaycypsHistoricFoliosImport;
use App\Imports\CellersPaycypsHistoricImport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CellersPaycypsHistoricController extends Controller
{
    public function store(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = CellersPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {
                if (Str::contains($fileName, '.xls')) {

                    $rows = preg_grep("/(\d{4}\s\d{2}\*{2}\s\*{4}\s\d{4})/", file($file));
                    $movements = [];

                    foreach ($rows as $row => $cont) {
                        $a = preg_split("/<\/td>/", $cont);
                        $b = array_map(function ($c) {
                            return Str::after($c, '>');
                        }, $a);


                        preg_match("/(\d{2}\/\d{2}\/\d{4})\s(\d{2}:\d{2}:\d{2})/", $b[1], $d);
                        $part = explode('/', $d[1]);
                        $tarjeta = str_replace('*', '', str_replace(' ', '', $b[3]));


                        CellersPaycypsHistoric::create([
                            'folio' => $b[0],
                            'fecha_operacion' => $part[2] . '-' . $part[1] . '-' . $part[0] . ' ' . $d[2],
                            'fecha_liq' => Carbon::createFromFormat('d/m/Y', $b[2])->format('Y-m-d'),
                            'tarjeta' => $tarjeta,
                            'banco' => $b[4],
                            'producto' => $b[5],
                            'importe_venta' => $b[6],
                            'importe_original' => $b[7],
                            'divisa' => $b[8],
                            'comision_cobrada' => $b[9],
                            'costo' => $b[10],
                            'autorizacion' => $b[11],
                            'tipo_operacion' => $b[12],
                            'tipo_bin' => Str::after($b[13], 'b'),
                            'terminal' => $b[14],
                            'comercio' => $b[15],
                            'ref2' => $b[16],
                            'ref3' => $b[17],
                            'ref4' => $b[18],
                            'ticket' => $b[19],
                            'codigo_respuesta' => $b[20],
                            'descripcion' => $b[21],
                            'file_name' => $fileName,
                        ]);
                    }
                    return back();

                } else {

                    $import = (new CellersPaycypsHistoricImport())->fromFile($fileName);

                    Excel::import($import, $file);
                }
            }
        }
        return back();
    }

    public function storeFolios(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $saved = CellersPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {

                if (Str::contains($fileName, 'liq')) {
                    $import = (new CellersPaycypsHistoricFoliosImport())->fromFile($fileName);

                    Excel::import($import, $file);
                }
            }
        }

        return back();
    }
}
