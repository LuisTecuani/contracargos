<?php

namespace Contracargos\Exports;

use Contracargos\Repsmediakey;
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
