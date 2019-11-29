<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Exporter;

class ExportsController extends Controller
{
    /**
     * Show to create a new export.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('exports.index');
    }

    /**
     * Download the export as a CSV file.
     *
     * @param string $filename
     * @param mixed $export
     */
    protected function download(string $filename, $export)
    {

        return $export->download($filename);
    }
}

