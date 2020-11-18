<?php

namespace App\Imports;

use App\AliadoPaycypsHistoric;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AliadoPaycypsHistoricImport implements ToModel, WithHeadingRow
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
        return new AliadoPaycypsHistoric([
            'folio' => $row['folio'],
            'fecha_operacion' => $row['fecha_operacion'],
            'fecha_liq' => $row['fecha_liq'],
            'tarjeta' => $row['tarjeta'],
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
