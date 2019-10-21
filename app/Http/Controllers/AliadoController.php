<?php

namespace App\Http\Controllers;

use App\FileProcessor;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repsaliado;
use App\RepsRechazadosAliado;
use App\respuestaBanorteAliado;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\ContracargosAliado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AliadoController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        $role = DB::table('consultas.users as u')
            ->select('u.role')
            ->where('u.id', '=', Auth::id())
            ->get();

        $cards = DB::table("consultas.contracargos_aliado as cm")
            ->leftJoin("consultas.repsaliado as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("aliado.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->orderBy('cm.id')
            ->get();

        return view("aliado.index", compact('cards', 'role'));
    }

    public function last()
    {


        $emails = DB::table("consultas.contracargos_aliado as cm")
            ->leftJoin("consultas.repsaliado as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("aliado.users as u", 'u.id', '=', 'rm.user_id')
            ->select('u.email')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->groupBy("u.email")
            ->get();


        return view("aliado.last", compact('emails'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $valid = DB::table("consultas.contracargos_aliado as cm")
                ->where([
                    ["cm.autorizacion", "=", $store[0]],
                    ["cm.tarjeta", "=", $store[1]],
                    ])
                ->get();
            if (count($valid) === 0)
            {
                $Contracargos = new ContracargosAliado();
                $Contracargos->autorizacion = $store[0];
                $Contracargos->tarjeta = $store[1];
                $Contracargos->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("aliado.index");

    }

    public function rechazados(ImportRepRequest $request)
    {
        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.reps_rechazados_aliado as ra')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $rejected = processRep($file);

                    foreach ($rejected as $row) {

                        RepsRechazadosAliado::create([

                            'tarjeta' => $row[0],

                            'user_id' => $row[1],

                            'fecha' => $row[2],

                            'terminacion' => substr($row[0], -4, 4),

                            'motivo' => trim($row['motivo']),

                            'monto' => $row[count($row)-4],

                            'source_file' => $source

                        ]);
                    }
                }
            }


        return back();
    }

    public function import(ImportRepRequest $request)
    {

        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.repsaliado as ra')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS')) {
                    $rep4 = accepRepToArray($rep10);

                    foreach ($rep4 as $rep3) {


                        Repsaliado::create([

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

    public function banorte(ImportRepRequest $request)
    {

        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);
        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.respuestas_banorte_aliado as ra')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $processed = processXml($file);

                foreach ($processed as $row) {

                    RespuestaBanorteAliado::create([
                        'comentarios' => $row['comentarios'],

                        'detalle_mensaje' => $row['detalleMensaje'],

                        'estatus' => $row['estatus'],

                        'user_id' => $row['numContrato'],

                        'num_control' => $row['numControl'],

                        'tarjeta' => $row['numTarjeta'],

                        'terminacion' => substr($row['numTarjeta'], -4, 4),

                        'monto' => $row['total'],

                        'fecha' => date('Y-m-d', strtotime(substr($row['numControl'], 0, 8))),

                        'source_file' => $source,

                    ]);
                }
            }
        }


        return back();
    }
}
