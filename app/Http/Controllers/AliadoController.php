<?php

namespace App\Http\Controllers;

use App\AliadoBlacklist;
use App\User;
use App\Repsaliado;
use App\FileProcessor;
use App\RepsRechazadosAliado;
use App\RespuestasBanorteAliado;
use http\Client\Request;
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
        return view("aliado.index");
    }

    /* public function index()
    {
        $role = User::role();

        DB::select('update contracargos_aliado c left join repsaliado r on r.autorizacion=c.autorizacion
                set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_aliado c join aliado.users u on u.id=c.user_id set c.email=u.email');

        $cards = Contracargosaliado::whereDate('created_at', today())->get();

        $cards2 = Contracargosaliado::get();

        return view("aliado.index", compact('cards', 'cards2', 'role'));
    } */

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $exist = Contracargosaliado::where([['autorizacion', $store[0]],['tarjeta', $store[1]]])->first();
            if (! $exist) {
                $Contracargos = new ContracargosAliado();
                $Contracargos->autorizacion = $store[0];
                $Contracargos->tarjeta = $store[1];
                $Contracargos->save();
            }
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

                    RespuestasBanorteAliado::create([
                        'comentarios' => $row['comentarios'] ?? null,

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

}
