<?php

namespace App\Http\Controllers;

use App\CellersPaycypsHistoric;
use App\Imports\CellersPaycypsHistoricFoliosImport;
use App\Imports\CellersPaycypsHistoricImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CellersPaycypsHistoricController extends Controller
{
    public function store(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = CellersPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {
                $import = (new CellersPaycypsHistoricImport())->fromFile($fileName);

                Excel::import($import, $file);
            }
        }

        return back();
    }

    public function storeFolios(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $saved = CellersPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {

                if (Str::contains($fileName, 'liq')) {
                    $import = (new CellersPaycypsHistoricFoliosImport())->fromFile($fileName);

                    Excel::import($import, $file);
                }
            }
        }

        return back();
    }
}
