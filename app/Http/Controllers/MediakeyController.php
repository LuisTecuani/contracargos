<?php

namespace App\Http\Controllers;

use App\User;
use App\Repsmediakey;
use App\FileProcessor;
use App\RespuestasBanorteMediakey;
use Illuminate\Support\Str;
use App\ContracargosMediakey;
use App\RepsRechazadosMediakey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreAdminRequest;


class MediakeyController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->database = ENV('DB_DATABASE1');
        $this->model = "App\Reps$this->database";
        $str = Str::title($this->database);
        $this->model2 = "App\Contracargos$str";
        $this->fileP = $filep;
    }

    public function index()
    {
        $role = User::role();

        DB::select('update contracargos_mediakey c left join repsmediakey r on r.autorizacion=c.autorizacion 
                set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_mediakey c join mediakey.users u on u.id=c.user_id set c.email=u.email');

        $cards = ContracargosMediakey::get();

        $cards2 = ContracargosMediakey::get();

        return view("mediakey.index", compact('cards', 'cards2', 'role'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            if(preg_match('/^\d{1,4}$/', $store[1])){
                    $Contracargos = new $this->model2;
                    $Contracargos->autorizacion = $store[0];
                    $Contracargos->tarjeta = $store[1];
                    $Contracargos->save();}}

        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("$this->database.index");
    }

    public function store2(StoreUserRequest $request)
    {
        $Contracargos = new $this->model2;
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("$this->database.index");
    }

    public function import(ImportRepRequest $request)
    {
        $files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach($files as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');
            $valid = DB::table('consultas.repsmediakey')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $responses = processRep($file);

                foreach ($responses[1] as $row) {
                    $row[10] = Str::after($row[10], 'C0000000');

                    Repsmediakey::create([
                        'tarjeta' => $row[0],
                        'estatus' => 'Aprobada',
                        'terminacion' => substr($row[0], -4, 4),
                        'user_id' => $row[10],
                        'fecha' => $row[2],
                        'autorizacion' => $row[5],
                        'monto' => $row[8],
                        'source_file' => $source
                    ]);
                }

                foreach ($responses[0] as $row) {
                    $row['id'] = Str::after($row[count($row)-2], 'C0000000');

                    RepsMediakey::create([
                        'tarjeta' => $row[0],
                        'estatus' => 'Rechazada',
                        'user_id' => $row['id'],
                        'fecha' => $row[2],
                        'terminacion' => substr($row[0], -4, 4),
                        'motivo_rechazo' => trim($row['motivo']),
                        'monto' => $row[count($row)-5],
                        'source_file' => $source
                        ]);
                    }
            }
        }
        return redirect()->route("$this->database.index");
    }


    public function banorte(ImportRepRequest $request)
    {
        $files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Respuestas Registradas: ' . $total);
        foreach ($files as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.respuestas_banorte_mediakey')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                $processed = processXml($file);

                foreach ($processed as $row) {

                    if (empty($row['codigoAutorizacion'])) {
                        $row['codigoAutorizacion'] = null;
                    }

                    RespuestasBanorteMediakey::create([
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
}
