<?php

namespace App\Http\Controllers;

use App\Repsmediakey;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\ContracargosMediakey;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class MediakeyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->database = ENV('DB_DATABASE1');

        $this->model = "App\Reps$this->database";

        $str = Str::title($this->database);
        $this->model2 = "App\Contracargos$str";
    }


    public function index()
    {

        $role = DB::table('consultas.users as u')
            ->select('u.role')
            ->where('u.id', '=', Auth::id())
            ->get();

        $cards = DB::table("consultas.contracargos_mediakey as cm")
            ->leftJoin("consultas.repsmediakey as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("mediakey.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->orderBy('cm.id')
            ->paginate(25);

        $cards2 = DB::table("consultas.contracargos_mediakey as cm")
            ->leftJoin("consultas.repsmediakey as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("mediakey.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereDate('cm.created_at', today())
            ->orderBy('cm.id')
            ->get();

        return view("$this->database.index", compact('cards', 'cards2', 'role'));
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
            $aut = strlen($store[0]);
            if($aut < 7 && $aut > 0) {
                if(preg_match('/^\d{1,4}$/', $store[1])){
                    $Contracargos = new $this->model2;
                    $Contracargos->autorizacion = $store[0];
                    $Contracargos->tarjeta = $store[1];
                    $Contracargos->save();}}

        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("$this->database.index");

    }

    public function store2(Request $request)
    {
        $request->validate([
            'autorizacion' => 'required|digits:6|numeric',
            'terminacion' => 'required|digits:4|numeric',
        ]);
        $Contracargos = new $this->model2;
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("$this->database.index");
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

            $rep10 = file_get_contents($file);

            if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS')) {
                $rep9 = Str::after($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS                        ');
                $rep8 = Str::before($rep9, 'Totales:                                                                           ');
                $rep7 = Arr::sort(preg_split("/\n/", $rep8));
                $rep6 = preg_grep("/([[:digit:]]{16})/", $rep7);

                foreach ($rep6 as $cls => $vls) {
                    $rep5[$cls] = preg_grep("/\S/", preg_split("/\s/", $vls));
                }

                $rep4 = fixKeys($rep5);

                foreach ($rep4 as $rep3) {

                    $rep3[10] = Str::after($rep3[10], 'C0000000');

                    Repsmediakey::create([

                        'tarjeta' => $rep3[0],

                        'terminacion' => substr($rep3[0], -4, 4),

                        'user_id' => $rep3[10],

                        'fecha' => $rep3[2],

                        'autorizacion' => $rep3[5],

                        'monto' => $rep3[8]

                    ]);

                }


            }
        }
        return redirect()->route("$this->database.index");
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}


