<?php

namespace App\Http\Controllers;

use App\Imports\RepsmediakeyImport;
use Illuminate\Http\Request;
use App\Exports\RepsmediakeyExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class MyController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function importExportView()
    {
        return view('import');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new RepsmediakeyExport, 'repsmediakey.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */

}
