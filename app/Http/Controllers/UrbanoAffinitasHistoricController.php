<?php

namespace App\Http\Controllers;

use App\UrbanoAffinitasHistoric;
use App\Imports\UrbanoAffinitasHistoricImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UrbanoAffinitasHistoricController extends Controller
{
    public function store(Request $request)
    {
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = UrbanoAffinitasHistoric::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {
                $import = (new UrbanoAffinitasHistoricImport())->fromFile($fileName);

                Excel::import($import, $file);
            }
        }

        return back();
    }
}
