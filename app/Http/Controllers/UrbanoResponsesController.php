<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRepRequest;
use App\Repsurbano;
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
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = Repsurbano::where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
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
