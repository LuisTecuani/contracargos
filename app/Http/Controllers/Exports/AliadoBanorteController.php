<?php

namespace App\Http\Controllers\Exports;

use App\Exports\AliadoBanorteExport;
use Illuminate\Http\Request;
use App\Http\Controllers\ExportsController;

class AliadoBanorteController extends ExportsController
{
    public function export()
    {
        $today = now()->format('Y-m-d');

        return (new AliadoBanorteExport)
            ->download("motivos-de-cancelacion-$today.csv");
    }

}
