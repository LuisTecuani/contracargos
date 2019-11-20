<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliadoBanorteController extends Controller
{
    public function index()
    {
        return view('aliado.banorte.index');
    }

    public function ftp(Request $request)
    {
        $file = $request->file('file');
        $rows = preg_grep("/(801089727)/", file($file));;

        foreach ($rows as $row => $cont) {

                $users[$row] = substr($cont, 9, 6);
            }


        return view('/aliado/banorte/results', compact('users'));
    }
}
