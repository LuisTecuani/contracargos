<?php

namespace App\Http\Controllers;

use App\SanbornsDevolucion;
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
        return view("sanbornscobro.index", compact('role'));
    }

    public function storeCharges(ImportSanbornsRequest $request)
    {
        $files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Files Registrados: ' . $total);

        foreach ($files as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');
            $validation = SanbornsCobro::where('source', $source)->get();

            if (count($validation) < 1) {
                $data = file_get_contents($file);
                $dataFilter = SanbornsTxtToArray($data);
                foreach ($dataFilter as $dataInsert) {
                    SanbornsCobro::create([
                        'cuenta' => substr($dataInsert, 0, 13),
                        'fecha' => substr($dataInsert, 13, 8),
                        'importe' => substr($dataInsert, 43, 8),
                        'respuesta' => substr($dataInsert, 53, 2),
                        'referencia' => substr($dataInsert, 55, 13),
                        'source' => $source
                    ]);
                }
            }
        }
        return back();
    }
    public function storeReturns(ImportSanbornsRequest $request)
    {
        $files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Files Registrados: ' . $total);

        foreach ($files as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');
            $validation = SanbornsDevolucion::where('source', $source)->get();

            if (count($validation) < 1) {
                $data = file_get_contents($file);
                $dataFilter = SanbornsTxtToArray($data);
                foreach ($dataFilter as $dataInsert) {
                    SanbornsDevolucion::create([
                        'cuenta' => substr($dataInsert, 0, 13),
                        'fecha' => substr($dataInsert, 13, 8),
                        'importe' => substr($dataInsert, 43, 8),
                        'referencia' => substr($dataInsert, 55, 13),
                        'source' => $source
                    ]);
                }
            }
        }
        return back();
    }
}
