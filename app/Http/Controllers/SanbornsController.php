<?php

namespace App\Http\Controllers;


use App\BonificacionSanborns;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SanbornsController extends Controller
{
    public function index()
    {
        return view('sanborns.index');
    }

    public function store(Request $request)
    {
        $file = preg_grep("/([[:digit:]]{7})/",file($request->file('file')));
        $rawRows = [];

        foreach ($file as $cls => $vls) {

            $rawRows[$cls] = preg_grep("/\S/", preg_split("/\s/", $vls));

        }
        $rows = fixKeys($rawRows);

        foreach ($rows as $row) {


            BonificacionSanborns::create([

                'row_in_file' => $row[0],

                'sanborns_id' => $row[1],

                'email'=> $row[2],

                'cantidad_cargos'=> $row[3],

                'monto'=> $row[5],


            ]);
        }
        return back();
    }
}
