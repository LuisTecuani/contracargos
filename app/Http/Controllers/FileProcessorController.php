<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileProcessorController extends Controller
{
    public function store(Request $request)
    {
        $files = $request->file('files');

        foreach ($files as $file) {
            $source = explode('.', $file->getClientOriginalName());

            if ($source[1] = 'rep') {
                foreach (config('platforms') as $platform) {
                    if (Str::endsWith($source[0], substr($platform['affinitas'], -4))) {
                        $valid = $platform['reps_model']::where('source_file', 'like', $source[0])->get();

                        if (count($valid) === 0) {
                            $responses = processRep($file);

                            foreach ($responses[0] as $row) {
                                $platform['reps_model']::create([
                                    'tarjeta' => $row[0],
                                    'estatus' => 'Declinada',
                                    'user_id' => $row[1],
                                    'fecha' => $row[2],
                                    'terminacion' => substr($row[0], -4, 4),
                                    'detalle_mensaje' => trim($row['motivo']),
                                    'autorizacion' => 'N/A',
                                    'monto' => $row[count($row)-4],
                                    'source_file' => $source[0]
                                ]);
                            }
                            foreach ($responses[1] as $row) {
                                $platform['reps_model']::create([
                                    'tarjeta' => $row[0],
                                    'estatus' => 'Aprobada',
                                    'user_id' => $row[1],
                                    'fecha' => $row[2],
                                    'terminacion' => substr($row[0], -4, 4),
                                    'autorizacion' => $row[5],
                                    'monto' => $row[8],
                                    'detalle_mensaje' => 'Aprobado',
                                    'source_file' => $source[0]
                                ]);
                            }
                        }
                    }
                    echo ' falta feedback no seas flojo';
                }
            }
        }
        return back();
    }
}
