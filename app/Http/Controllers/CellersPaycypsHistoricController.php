<?php

namespace App\Http\Controllers;

use App\CellersPaycypsHistoric;
use App\Imports\CellersPaycypsHistoricImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CellersPaycypsHistoricController extends Controller
{
    public function store(Request $request)
    {
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
}
