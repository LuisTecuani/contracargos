<?php

namespace App\Http\Controllers;

use App\Exports\AliadoBanorteExport;

class AliadoFileMakingController extends Controller
{
    public function index()
    {
        return view("aliado.file_making.index");
    }

    public function exportBanorte()
    {
        return (new AliadoBanorteExport)
            ->download('aliado-banorte-'.now()->format('Y-m-d').'.csv');
    }
}
