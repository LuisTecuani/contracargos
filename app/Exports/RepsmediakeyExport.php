<?php

namespace App\Exports;

use App\Repsmediakey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class RepsmediakeyExport implements FromCollection
{

    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Repsmediakey::all();

    }
}
