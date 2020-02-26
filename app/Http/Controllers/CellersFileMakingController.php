<?php

namespace App\Http\Controllers;

use App\Exports\CellersBanorteExport;

class CellersFileMakingController extends Controller
{
    public function index()
    {
        return view("cellers.file_making.index");
    }

    public function exportBanorte()
    {
        return (new CellersBanorteExport)
            ->download('cellers-banorte-'.now()->format('Y-m-d').'.csv');
    }
}
