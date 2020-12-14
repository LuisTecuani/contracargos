<?php

namespace App\Imports;

use App\UrbanoPaycypsHistoric;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UrbanoPaycypsHistoricFoliosImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected $fileName;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $folio = $row['folio'];
        preg_match("/(\d{2}\/\d{2}\/\d{4})\s(\d{2}:\d{2})/", $row['fecha_operacion'], $d);
        $part = explode('/', $d[1]);
        $tarjeta = str_replace('*', '',str_replace(' ','',$row['tarjeta']));

        return new UrbanoPaycypsHistoric([
            'folio' => $folio,
            'fecha_operacion' => $part[2].'-'.$part[1].'-'.$part[0].' '.$d[2],
            'fecha_liq' => Carbon::createFromFormat('d/m/Y', $row['fecha_liq'])->format('Y-m-d'),
            'tarjeta' => $tarjeta,
            'banco' => $row['banco'],
            'importe_venta' => $row['importe_venta'],
            'comision_cobrada' => $row['comision_cobrada'],
            'costo' => $row['costo'],
            'autorizacion' => $row['autorizacion'],
            'tipo_operacion' => $row['tipo_operacion'],
            'tipo_bin' => $row['tipo_bin'],
            'terminal' => $row['comercio'],
            'comercio' => $row['ref2'],
            'ref3' => $row['ref3'],
            'ticket' => $row['ticket'],
            'file_name' => $this->fileName,
        ]);
    }

    public function fromFile(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
}
