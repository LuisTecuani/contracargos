<?php

namespace App\Imports;

use App\CellersPaycypsBill;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CellersPaycypsImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected  $folio;
    protected $fileName;
    private $rows = 0;

    public function fromRequest(string $fileName, string $folio)
    {
        $this->fileName = $fileName;
        $this->folio = $folio;
        return $this;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        ++$this->rows;

        return new CellersPaycypsBill([
            'user_id' => $row['nombre'],
            'tdc' => $row['cuenta'],
            'amount' => $row['importe'] * 100,
            'bill_day' => $row['dia'],
            'paycyps_id' => $this->folio.'_'.$this->rows,
            'file_name' => $this->fileName,
        ]);
    }
}
