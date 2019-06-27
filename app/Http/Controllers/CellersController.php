<?php

namespace App\Http\Controllers;

use App\FileProcessor;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repscellers;
use App\CreditCards;
use App\Helpers\Funciones;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\ContracargosCellers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAdminRequest;

class CellersController extends Controller
{
    public function __construct(FileProcessor $filep) {
        $this->middleware('auth');

        $this->database = ENV('DB_DATABASE2');

        $this->model = "App\Reps$this->database";

        $this->fileP = $filep;
    }


    public function index() {
        $role = DB::table('consultas.users as u')
            ->select('u.role')
            ->where('u.id', '=', Auth::id())
            ->get();

        $cards = DB::table("consultas.contracargos_cellers as cm")
            ->leftJoin("consultas.repscellers as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("cellers.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->orderBy('cm.id')
            ->get();

        $cards2 = DB::table("consultas.contracargos_cellers as cm")
            ->leftJoin("consultas.repscellers as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("cellers.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereDate('cm.created_at', today())
            ->orderBy('cm.id')
            ->get();

        return view("cellers.index", compact('cards', 'cards2', 'role'));

    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $Contracargos = new ContracargosCellers();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');
        return redirect()->route("cellers.index");
        }

        public function store2(StoreUserRequest $request)
    {
        $Contracargos = new ContracargosCellers();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("cellers.index");
    }



    public function import(ImportRepRequest $request)
    {
        $archivos     =   $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach($archivos as $file)
        {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.repscellers as ra')
                ->where('source_file', 'like', $source)->get();


            if (count($valid) === 0)
            {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
                {
                    $rep4 = accepRepToArray($rep10);

                    foreach ($rep4 as $rep3)
                    {


                        $this->model::create([

                            'tarjeta' => $rep3[0],

                            'user_id' => $rep3[1],

                            'fecha' => $rep3[2],

                            'terminacion' => substr($rep3[0],-4,4),

                            'autorizacion' => $rep3[5],

                            'monto' => $rep3[8],

                            'source_file' => $source

                        ]);
                    }
                }
            }
        }

        return back();

    }

}
