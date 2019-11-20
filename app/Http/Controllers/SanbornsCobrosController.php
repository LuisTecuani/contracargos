<?php

namespace App\Http\Controllers;

use App\SanbornsDevolucionCobro;
use App\User;
use App\SanbornsCobro;
use Illuminate\Support\Str;
use App\SanbornsDevolucion;
use Illuminate\Http\Request;
use App\SanbornsTotalCobros;
use App\SanbornsTotalDevoluciones;
use Illuminate\Support\Facades\DB;
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

    public function storeChargesReturns(ImportSanbornsRequest $request){

        $files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Files Registrados: ' . $total);

        foreach ($files as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');
            $validation = SanbornsDevolucionCobro::where('source', $source)->get();
            $returns =strpos($source, 'DEVWS');
            $charges = strpos($source, 'CGOWS');

            if (count($validation) < 1) {
                $data = file_get_contents($file);
                if ($charges !== false) {
                    //dd('Registro de cargos');
                    $dataFilter = SanbornsTxtToArray($data);
                    foreach ($dataFilter as $dataInsert) {
                        SanbornsDevolucionCobro::create([
                            'cuenta' => substr($dataInsert, 0, 13),
                            'fecha' => substr($dataInsert, 13, 8),
                            'importe' => substr($dataInsert, 43, 8),
                            'respuesta' => substr($dataInsert, 53, 2),
                            'referencia' => substr($dataInsert, 55, 13),
                            'source' => $source,
                            'tipo' => "cargo"
                        ]);
                    }
                } elseif ($returns !== false) {
                    //dd('registro de devoluciones');
                    $dataFilter = SanbornsTxtToArray($data);
                    foreach ($dataFilter as $dataInsert) {
                        SanbornsDevolucionCobro::create([
                            'cuenta' => substr($dataInsert, 0, 13),
                            'fecha' => substr($dataInsert, 13, 8),
                            'importe' => substr($dataInsert, 43, 8),
                            'referencia' => substr($dataInsert, 55, 13),
                            'source' => $source,
                            'tipo' => "devolucion"
                        ]);
                    }
                }
            }
        }
        return back();
    }

    public function numberChargesReturns()
    {
        SanbornsTotalDevoluciones::truncate();
        SanbornsTotalCobros::truncate();

        DB::select('INSERT INTO sanborns_total_devoluciones (cuenta, veces_devuelto, total_devoluciones) 
                    SELECT cuenta, COUNT(*), sum(importe)
                    FROM sanborns_devoluciones 
                    group by cuenta');

        DB::select('INSERT INTO sanborns_total_cobros(cuenta, veces_cobrado, total_cobros)
                    SELECT cuenta, COUNT(*), sum(importe) FROM sanborns_cobros 
                    where respuesta = "00" 
                    group by cuenta');

        Session()->flash('message', 'Total Devoluciones Y Cobros Actualizado  ');

        return back();
    }
}
