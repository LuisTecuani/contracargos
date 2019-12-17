<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRepRequest;
use App\Repsaliado;
use App\RespuestasBanorteAliado;
use Illuminate\Support\Str;

class AliadoResponsesController extends Controller
{
    public function index()
    {
        return view("aliado.responses.index");
    }

    public function storeReps(ImportRepRequest $request)
    {
        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = Repsaliado::where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $responses = processRep($file);

                foreach ($responses[0] as $row) {

                    Repsaliado::create([

                        'tarjeta' => $row[0],

                        'estatus' => 'Declinada',

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

                        'motivo_rechazo' => 'Aprobado',

                        'source_file' => $source

                    ]);
                }
            }
        }


        return back();
    }


    public function storePdf(ImportRepRequest $request)
    {

        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);
        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = RespuestasBanorteAliado::where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $processed = processPdf($file);

                foreach ($processed as $row) {

                    RespuestasBanorteAliado::create([
                        'comentarios' => $row[10] ?? null,

                        'detalle_mensaje' => $row[8] ?? null,

                        'autorizacion' => $row[11] ?? null,

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
