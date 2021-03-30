<?php

namespace App\Http\Controllers;

use App\Imports\UrbanoPaycypsHistoricFoliosImport;
use App\UrbanoPaycypsHistoric;
use App\Imports\UrbanoPaycypsHistoricImport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UrbanoPaycypsHistoricController extends Controller
{
    public function store(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = UrbanoPaycypsHistoric::where('file_name', 'like', $fileName)->get();
            if (count($saved) === 0) {

                if (Str::contains($fileName, '.xls')) {
                    $dataRows = preg_grep('/\d{4}\s\d{2}\*{2}\s\*{4}\s\d{4}/', file($file));

                    foreach ($dataRows as $row) {
                        $cleanedRow = $this->prepareRow($row);


                        UrbanoPaycypsHistoric::create([
                            'Folio' => $cleanedRow[0],
                            'Fecha_Operacion' => Carbon::createFromFormat('d/m/Y H:i:s', $cleanedRow[1])->format('Y-m-d H:i:s'),
                            'Fecha_Liq' => Carbon::createFromFormat('d/m/Y', $cleanedRow[2])->format('Y-m-d'),
                            'Tarjeta' => str_replace(' ', '', str_replace('*', '', $cleanedRow[3])),
                            'Banco' => $cleanedRow[4],
                            'Producto' => $cleanedRow[5],
                            'Importe_Venta' => $cleanedRow[6],
                            'Importe_Original' => $cleanedRow[7],
                            'Divisa' => $cleanedRow[8],
                            'Comision_Cobrada' => $cleanedRow[9],
                            'Costo' => $cleanedRow[10],
                            'Autorizacion' => $cleanedRow[11],
                            'Tipo_Operacion' => $cleanedRow[12],
                            'Tipo_Bin' => $cleanedRow[13],
                            'Terminal' => $cleanedRow[14],
                            'Comercio' => $cleanedRow[15],
                            'Ref2' => $cleanedRow[16],
                            'Ref3' => $cleanedRow[17],
                            'Ref4' => $cleanedRow[18],
                            'Ticket' => $cleanedRow[19],
                            'Codigo_Respuesta' => $cleanedRow[20],
                            'Descripcion' => $cleanedRow[21],
                            'file_name' => $fileName,
                        ]);
                    }
                } else {
                $import = (new UrbanoPaycypsHistoricImport())->fromFile($fileName);

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
            $saved = UrbanoPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {

                if (Str::contains($fileName, 'liq')) {
                    $import = (new UrbanoPaycypsHistoricFoliosImport())->fromFile($fileName);

                    Excel::import($import, $file);
                }
            }
        }

        return back();
    }

    /**
     * @param $row
     * @return array|string[]
     */
    public function prepareRow($row)
    {
        return array_map(function ($a) {
            return Str::after($a, '>');
        }, preg_split('/<\/td>/', $row));
    }
}
