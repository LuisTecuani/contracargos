<?php

namespace App\Http\Controllers;

use App\AliadoPaycypsBill;
use App\AliadoPaycypsHistoric;
use App\Imports\AliadoPaycypsHistoricFoliosImport;
use App\Imports\AliadoPaycypsHistoricImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AliadoPaycypsHistoricController extends Controller
{
    public function store(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = AliadoPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {

                if (Str::contains($fileName, '.xls')) {

                    $rows = preg_grep("/(<td>)/", file($file));
                    $movements = [];

                    foreach ($rows as $row => $cont) {
                        $b = preg_replace("/<td style=\"mso-number-format:\d*;\">/", "<td>", $cont);
                        $a = preg_split("/<td>/", $b);
                        $d = preg_replace("/<\/td>*/", "", $a);
                        $c = preg_replace("/\r\n/", "", $d);

                    dd($c,  $c[14],Str::after($c[14],'b'));

                        preg_match("/(\d{2}\/\d{2}\/\d{4})\s(\d{2}:\d{2}:\d{2})/", $c[2], $d);
                        $part = explode('/', $d[1]);
                        $tarjeta = str_replace('*', '',str_replace(' ','',$c[4]));
                        $ref2 = $c[17];

                        if (!$c[18]) {
                            $c[18] = AliadoPaycypsBill::select('paycyps_id')
                                    ->whereRaw("right(tdc, 4) = right($tarjeta, 4)")
                                    ->whereRaw("left(tdc, 6) = left($tarjeta, 6)")
                                    ->whereRaw("$ref2 = substr(paycyps_id, 4)")
                                    ->first()->paycyps_id ?? null;
                        }

                        AliadoPaycypsHistoric::create([
                            'folio' => $c[2],
                            'fecha_operacion' => $part[2].'-'.$part[1].'-'.$part[0].' '.$d[2],
                            'fecha_liq' => Carbon::createFromFormat('d/m/Y', $c[3])->format('Y-m-d'),
                            'tarjeta' => $tarjeta,
                            'banco' => $c[5],
                            'producto' => $c[6],
                            'importe_venta' => $c[7],
                            'importe_original' => $c[8],
                            'divisa' => $c[9],
                            'comision_cobrada' => $c[10],
                            'costo' => $c[11],
                            'autorizacion' => $c[12],
                            'tipo_operacion' => $c[13],
                            'tipo_bin' => Str::after($c[14],'b'),
                            'terminal' => $c[15],
                            'comercio' => $c[16],
                            'ref2' => $ref2,
                            'ref3' => $c[18],
                            'ref4' => $c[19],
                            'ticket' => $c[20],
                            'codigo_respuesta' => $c[21],
                            'descripcion' => $c[22],
                            'file_name' => $fileName,
                        ]);

                    }
                    return back();

                } else {

                    $import = (new AliadoPaycypsHistoricImport())->fromFile($fileName);

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
            $saved = AliadoPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {

                if (Str::contains($fileName, 'liq')) {
                    $import = (new AliadoPaycypsHistoricFoliosImport())->fromFile($fileName);

                    Excel::import($import, $file);
                }
            }
        }

        return back();
    }
}
