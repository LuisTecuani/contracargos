<?php

namespace App\Http\Controllers;

use App\BinsHistoric;
use App\Imports\BinsHistoricImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BinsHistoricController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bins = BinsHistoric::with('bins')
            ->selectRaw('bin, sum(accepted) as a, sum(rejected) as r, sum(accepted) + sum(rejected) as t')
            ->groupBy('bin')
            ->orderBy('t', 'desc')
            ->get();


        return view("bins.historic.index", compact('bins'));
    }

    public function import(Request $request)
    {
        $file = $request->file;

        Excel::import(new BinsHistoricImport, $file);

        return back();
    }
}
