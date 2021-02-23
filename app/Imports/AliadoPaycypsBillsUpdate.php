<?php

namespace App\Imports;

use App\AliadoPaycypsBill;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AliadoPaycypsBillsUpdate implements ToModel, WithHeadingRow
{
    use Importable;

    protected $fileName;
    protected $deletedAt;

    public function fromRequest(string $fileName, string $deletedAt)
    {
        $this->fileName = $fileName;
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
            $entry = AliadoPaycypsBill::where('tdc','like', $row['cuenta'])->get();

            foreach ($entry as $row) {
                $row->deleted_at = $this->deletedAt;
                $row->save();
            }
    }
}
