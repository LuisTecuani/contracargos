<?php

namespace App\Imports;

use App\UrbanoPaycypsBill;
use App\UrbanoPaycypsHistoric;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UrbanoPaycypsHistoricImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected $fileName;

    public function fromFile(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        preg_match("/(\d{2}\/\d{2}\/\d{4})\s(\d{2}:\d{2}:\d{2})/", $row['fecha_operacion'], $d);
        $part = explode('/', $d[1]);
        $tarjeta = str_replace('*', '', str_replace(' ', '', $row['tarjeta']));
        $ref2 = $row['ref2'];

        if (!$row['ref3']) {
            $row['ref3'] = UrbanoPaycypsBill::select('paycyps_id')
                    ->whereRaw("right(tdc, 4) = right($tarjeta, 4)")
                    ->whereRaw("left(tdc, 6) = left($tarjeta, 6)")
                    ->whereRaw("$ref2 = substr(paycyps_id, 4)")
                    ->first()->paycyps_id ?? null;
        }

        return new UrbanoPaycypsHistoric([
            'folio' => $row['folio'],
            'fecha_operacion' => $part[2] . '-' . $part[1] . '-' . $part[0] . ' ' . $d[2],
            'fecha_liq' => Carbon::createFromFormat('d/m/Y', $row['fecha_liq'])->format('Y-m-d'),
            'tarjeta' => $tarjeta,
            'banco' => $row['banco'],
            'producto' => $row['producto'],
            'importe_venta' => $row['importe_venta'],
            'importe_original' => $row['importe_original'],
            'divisa' => $row['divisa'],
            'comision_cobrada' => $row['comision_cobrada'],
            'costo' => $row['costo'],
            'autorizacion' => $row['autorizacion'],
            'tipo_operacion' => $row['tipo_operacion'],
            'tipo_bin' => $row['tipo_bin'],
            'terminal' => $row['terminal'],
            'comercio' => $row['comercio'],
            'ref2' => $row['ref2'],
            'ref3' => $row['ref3'],
            'ref4' => $row['ref4'],
            'ticket' => $row['ticket'],
            'codigo_respuesta' => $row['codigo_respuesta'],
            'descripcion' => $row['descripcion'],
            'file_name' => $this->fileName,
        ]);
    }
}
