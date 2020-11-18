<?php

namespace App\Imports;

use App\UrbanoAffinitasHistoric;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UrbanoAffinitasHistoricImport implements ToModel, WithHeadingRow
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
        return new UrbanoAffinitasHistoric([
            'fecha' => $row['fecha'],
            'hora' => $row['hora'],
            'corporativo' => $row['corporativo'],
            'comercio' => $row['comercio'],
            'sucursal' => $row['sucursal'],
            'afiliacion' => $row['afiliacion'],
            'operacion' => $row['operacion'],
            'referencia' => $row['referencia'],
            'id_tpv' => $row['id_tpv'],
            'num_serie' => $row['num_serie'],
            'transaccion' => $row['transaccion'],
            'modo_entrada' => $row['modo_entrada'],
            'monto' => $row['monto'],
            'monto_adicional' => $row['monto_adicional'],
            'cash_back' => $row['cash_back'],
            'monto_total' => $row['monto_total'],
            'mesero' => $row['mesero'],
            'moneda' => $row['moneda'],
            'iso' => $row['iso'],
            'arqc' => $row['arqc'],
            'trace_number' => $row['trace_number'],
            'respuesta' => $row['respuesta'],
            'autorizacion' => $row['autorizacion'],
            'resp' => $row['resp'],
            'numero_de_tarjeta' => $row['numero_de_tarjeta'],
            'banco_emisor' => $row['banco_emisor'],
            'marca' => $row['marca'],
            'naturaleza' => $row['naturaleza'],
            'tc' => $row['tc'],
            'q6' => $row['q6'],
            'meses' => $row['meses'],
            'plan' => $row['plan'],
            'file_name' => $this->fileName,
        ]);
    }
}
