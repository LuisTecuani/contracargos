<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRepRequest;
use App\Repsurbano;
use App\UserTdcUrbano;
use Illuminate\Support\Str;

class UrbanoResponsesController extends Controller
{
    public function index()
    {
        return view("urbano.responses.index");
    }

    public function storeReps(ImportRepRequest $request)
    {
        $archivos = $request->file('files');

        foreach ($archivos as $file) {
            $fileName = $file->getClientOriginalName();
            $source = Str::before($fileName, '.');
            $valid = Repsurbano::where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                if (Str::contains($fileName, 'pdf')) {

                    $processed = processPdf($file);

                    foreach ($processed as $row) {
                        $tarjeta = UserTdcUrbano::where('user_id', '=', $row[1])->firstOrFail()->number;

                        Repsurbano::create([
                            'tarjeta' => $tarjeta ?? null,
                            'estatus' => $row[4],
                            'user_id' => $row[1],
                            'fecha' => date('Y-m-d', strtotime(substr($row[3], 0, 8))),
                            'terminacion' => substr($tarjeta, -4, 4),
                            'detalle_mensaje' => $row[8] ?? null,
                            'autorizacion' => $row[11] ?? null,
                            'monto' => $row[6],
                            'source_file' => $source,
                        ]);
                    }
                    continue;
                }

                $responses = processRep($file);

                foreach ($responses[0] as $row) {

                    Repsurbano::create([
                        'tarjeta' => $row[0],
                        'estatus' => 'Declinada',
                        'user_id' => $row[1],
                        'fecha' => $row[2],
                        'terminacion' => substr($row[0], -4, 4),
                        'detalle_mensaje' => trim($row['motivo']),
                        'autorizacion' => 'N/A',
                        'monto' => $row[count($row)-4],
                        'source_file' => $source
                    ]);
                }

                foreach ($responses[1] as $row) {
                    Repsurbano::create([
                        'tarjeta' => $row[0],
                        'estatus' => 'Aprobada',
                        'user_id' => $row[1],
                        'fecha' => $row[2],
                        'terminacion' => substr($row[0], -4, 4),
                        'autorizacion' => $row[5],
                        'monto' => $row[8],
                        'detalle_mensaje' => 'Aprobado',
                        'source_file' => $source
                    ]);
                }
            }
        }

        return back();
    }
}
