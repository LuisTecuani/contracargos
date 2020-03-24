<?php

namespace App\Imports;

use App\BinsHistoric;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BinsHistoricImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new BinsHistoric([
            'bin' => $row['bin'],
            'accepted' => $row['aceptados'],
            'rejected'   => $row['rechazados'],
            'platform' => $row['plataforma'],
            'bill_bank' => $row['pasarela'],
        ]);
    }
}
