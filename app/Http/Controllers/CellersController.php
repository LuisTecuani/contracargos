<?php

namespace App\Http\Controllers;

use App\Repscellers;
use App\CreditCards;
use App\Helpers\Funciones;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\ContracargosCellers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CellersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->database = ENV('DB_DATABASE2');

        $this->model = "App\Reps$this->database";
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
            ->paginate(25);

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

    public function store(Request $request)
    {
        $request->validate([
            'autorizaciones' => 'regex:/[[0-9][[:punct:]][0-9]/i',
        ]);
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $Contracargos = new ContracargosCellers();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("cellers.index");

    }

    public function store2(Request $request)
    {
        $request->validate([
            'autorizacion' => 'required|digits:6|numeric',
            'terminacion' => 'required|digits:4|numeric',
        ]);
        $Contracargos = new ContracargosCellers();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("cellers.index");
    }



    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required'
        ]);
        $archivos     =   $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach($archivos as $file)
        {
            $table = "reps$this->database";

            $valid = check_file_existence($file, $table);

            if (count($valid) === 0)
            {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
                {
                    $rep4 = accep_rep_to_array($rep10);

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
