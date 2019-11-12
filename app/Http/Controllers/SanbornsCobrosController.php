<?php

namespace App\Http\Controllers;

use App\User;
use App\SanbornsCobro;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ImportSanbornsRequest;


class SanbornsCobrosController extends Controller
{
    public function index()
    {
        $role = User::role();
        $prueba = SanbornsCobro::get();

       // dd($prueba);
        return view("sanbornscobro.index", compact('role'));
    }

    public function store(ImportSanbornsRequest $request)
    {
    	$files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Files Registrados: ' . $total);

        foreach ($files as $file) {
            $source= Str::before($file->getClientOriginalName(), '.');
            dd($source);
		}
    	return back();
    }
}
