<?php

namespace App\Imports;

use App\CellersPaycypsBill;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CellersPaycypsImport implements ToModel, WithHeadingRow
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
        return new CellersPaycypsBill([
            'user_id' => $row['nombre'],
            'tdc' => $row['cuenta'],
            'amount' => $row['importe'] * 100,
            'bill_day' => $row['dia'],
            'file_name' => $this->fileName,
        ]);
    }
}
