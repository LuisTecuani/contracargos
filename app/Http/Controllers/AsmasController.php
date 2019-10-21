<?php

namespace App\Http\Controllers;

use App\FileProcessor;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repsasmas;
use App\ContracargosAsmas;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AsmasController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }


    public function index()
    {


        return view("asmas.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $Contracargos = new ContracargosAsmas();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("asmas.index");

    }

    public function store2(StoreUserRequest $request)
    {
        $Contracargos = new ContracargosAsmas();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("asmas.index");
    }

    public function import(ImportRepRequest $request)
    {
        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.reps_aliado_rechazados')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                    $rep4 = processRep($file);

                    foreach ($rep4 as $rep3) {


                        Repsasmas::create([

                            'tarjeta' => $rep3[0],

                            'user_id' => $rep3[1],

                            'fecha' => $rep3[2],

                            'terminacion' => substr($rep3[0], -4, 4),

                            'autorizacion' => $rep3[5],

                            'monto' => $rep3[8],

                            'source_file' => $source

                        ]);
                    }
            }
        }

        return back();

    }

}
