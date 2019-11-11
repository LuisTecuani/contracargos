<?php

namespace App\Http\Controllers;

use App\User;
use App\Repsaliado;
use App\FileProcessor;
use App\RepsRechazadosAliado;
use App\respuestaBanorteAliado;
use Illuminate\Support\Str;
use App\Contracargosaliado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreAdminRequest;
use Smalot\PdfParser\Parser;


class AliadoController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        $role = User::role();

        DB::select('update contracargos_aliado c left join repsaliado r on r.autorizacion=c.autorizacion 
                set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_aliado c join aliado.users u on u.id=c.user_id set c.email=u.email');

        $cards = Contracargosaliado::whereDate('created_at', today())->get();

        $cards2 = Contracargosaliado::get();

        return view("aliado.index", compact('cards', 'cards2', 'role'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $Contracargos = new ContracargosAliado();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("aliado.index");

    }

    public function store2(StoreUserRequest $request)
    {
        $Contracargos = new ContracargosAliado();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("aliado.index");
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
                $responses = processRep($file);

                    foreach ($responses[0] as $row) {

                        Repsaliado::create([

                            'tarjeta' => $row[0],

                            'estatus' => 'Rechazada',

                            'user_id' => $row[1],

                            'fecha' => $row[2],

                            'terminacion' => substr($row[0], -4, 4),

                            'motivo_rechazo' => trim($row['motivo']),

                            'autorizacion' => 'N/A',

                            'monto' => $row[count($row)-4],

                            'source_file' => $source

                        ]);
                    }

                foreach ($responses[1] as $row) {


                    Repsaliado::create([

                        'tarjeta' => $row[0],

                        'estatus' => 'Aprobada',

                        'user_id' => $row[1],

                        'fecha' => $row[2],

                        'terminacion' => substr($row[0], -4, 4),

                        'autorizacion' => $row[5],

                        'monto' => $row[8],

                        'motivo_rechazo' => 'N/A',

                        'source_file' => $source

                    ]);
                }
                }
            }


        return back();
    }

    public function accepted(ImportRepRequest $request)
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

                    if (empty($row['codigoAutorizacion'])) {
                        $row['codigoAutorizacion'] = null;
                    }

                    RespuestaBanorteAliado::create([
                        'comentarios' => $row['comentarios'],

                        'detalle_mensaje' => $row['detalleMensaje'],

                        'autorizacion' => $row['codigoAutorizacion'],

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

    public function banortePdf(ImportRepRequest $request)
    {

        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);
        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.respuestas_banorte_aliado as ra')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $processed = processPdf($file);

                foreach ($processed as $row) {

                    RespuestaBanorteAliado::create([
                        'comentarios' => $row[10],

                        'detalle_mensaje' => $row[8],

                        'autorizacion' => $row[11],

                        'estatus' => $row[4],

                        'user_id' => $row[1],

                        'num_control' => $row[3],

                        'tarjeta' => $row[5],

                        'terminacion' => substr($row[5], -4, 4),

                        'monto' => $row[6],

                        'fecha' => date('Y-m-d', strtotime(substr($row[3], 0, 8))),

                        'source_file' => $source,

                    ]);
                }
            }
        }


        return back();
    }
}
