<?php

namespace App\Http\Controllers;

use App\User;
use App\FileProcessor;
use App\SanbornsTotal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\SanbornsTotalCobros;
use App\SanbornsCheckAccounts;
use App\SanbornsDevolucionCobro;
use App\SanbornsTotalDevoluciones;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ImportSanbornsRequest;
use App\Http\Requests\SanbornsSearchesRequest;


class SanbornsCobrosController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $role = User::role();
        $SearchedData = SanbornsCheckAccounts::with('returns', 'charges')->first();

        dd($SearchedData);

        return view("sanbornscobro.index", compact('role', 'SearchedData'));
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
                if ($charges !== false) {// Registro de cargos
                    $dataFilter = SanbornsTxtToArray($data);
                    foreach ($dataFilter as $dataInsert) {
                        SanbornsDevolucionCobro::create([
                            'cuenta' => substr($dataInsert, 0, 13),
                            'fecha' => substr($dataInsert, 13, 8),
                            'importe' => substr($dataInsert, 43, 8),
                            'respuesta' => substr($dataInsert, 53, 2),
                            'referencia' => substr($dataInsert, 55, 13),
                            'source' => $source,
                            'tipo' => 'Cobro'
                        ]);
                    }
                } elseif ($returns !== false) { //registro de devoluciones
                    $dataFilter = SanbornsTxtToArray($data);
                    foreach ($dataFilter as $dataInsert) {
                        SanbornsDevolucionCobro::create([
                            'cuenta' => substr($dataInsert, 0, 13),
                            'fecha' => substr($dataInsert, 13, 8),
                            'importe' => substr($dataInsert, 43, 8),
                            'referencia' => substr($dataInsert, 55, 13),
                            'source' => $source,
                            'tipo' => 'Devolucion'
                        ]);
                    }
                }
            }
        }
        $this->numberChargesReturns();
        return back();
    }

    public function numberChargesReturns()
    {
        SanbornsTotalDevoluciones::truncate();
        SanbornsTotalCobros::truncate();

        DB::select('INSERT INTO sanborns_total_devoluciones (cuenta, veces_devuelto, total_devoluciones) 
                    SELECT cuenta, COUNT(*), sum(importe)
                    FROM sanborns_devoluciones_cobros
                    where respuesta is null group by cuenta');

        DB::select('INSERT INTO sanborns_total_cobros(cuenta, veces_cobrado, total_cobros)
                    SELECT cuenta, COUNT(*), sum(importe) 
                    FROM sanborns_devoluciones_cobros 
                    where respuesta = "00" group by cuenta');
    }

    public function search(SanbornsSearchesRequest $request){
        SanbornsCheckAccounts::truncate();
        $accounts = $request->input('sanborns_id');
        $arrayAccounts = preg_split("[\r\n]", $accounts);
        foreach ($arrayAccounts as $array){
            $SanbornsCheckAccounts = new SanbornsCheckAccounts();
            $SanbornsCheckAccounts->sanborns_id = $array;
            $SanbornsCheckAccounts->save();
        }
        return redirect()->route("sanbornscobro.index");
    }

    public function searchDetails(Request $request){
        $sanborns_id = $request->input('sanborns_id');
        $details = SanbornsDevolucionCobro::where('cuenta', $sanborns_id)->get();
        //dd($details);
        return view("sanbornscobro.details", compact('details'));
    }
}
