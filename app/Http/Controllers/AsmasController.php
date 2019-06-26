<?php

namespace App\Http\Controllers;

use App\Repsasmas;
use App\ContracargosAsmas;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AsmasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $role = DB::table('consultas.users as u')
            ->select('u.role')
            ->where('u.id', '=', Auth::id())
            ->get();

        $cards = DB::table("consultas.contracargos_asmas as cm")
            ->leftJoin("consultas.repsasmas as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("asmas.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->orderBy('cm.id')
            ->paginate(25);

        $cards2 = DB::table("consultas.contracargos_asmas as cm")
            ->leftJoin("consultas.repsasmas as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("asmas.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereDate('cm.created_at', today())
            ->orderBy('cm.id')
            ->get();

        return view("asmas.index", compact('cards', 'cards2', 'role'));
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
            $Contracargos = new ContracargosAsmas();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("asmas.index");

    }

    public function store2(Request $request)
    {
        $request->validate([
            'autorizacion' => 'required|digits:6|numeric',
            'terminacion' => 'required|digits:4|numeric',
        ]);
        $Contracargos = new ContracargosAsmas();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("asmas.index");
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required'
        ]);
        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.repsasmas')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS')) {
                    $rep4 = accep_rep_to_array($rep10);

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
        }

        return back();

    }

}
