<?php

namespace App\Http\Controllers;

use App\AliadoPaycypsHistoric;
use App\Imports\AliadoPaycypsHistoricImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AliadoPaycypsHistoricController extends Controller
{
    public function store(Request $request)
    {
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = AliadoPaycypsHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {
                $import = (new AliadoPaycypsHistoricImport())->fromFile($fileName);

                Excel::import($import, $file);
            }
        }

        return back();
    }
}
