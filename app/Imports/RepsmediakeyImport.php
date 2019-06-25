<?php

namespace Contracargos\Imports;

use Contracargos\Repsmediakey;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class RepsmediakeyImport implements ToModel, WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Repsmediakey([
            'tarjeta' => $row[0],
            'user_id' => $row[1],
            'fecha'   => $row[2],
        'autorizacion' => $row[5],
            'monto'    => $row[8],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
