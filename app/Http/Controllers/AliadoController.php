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



    public function store2(StoreUserRequest $request)
    {
        $Contracargos = new ContracargosAliado();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("aliado.index");
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
