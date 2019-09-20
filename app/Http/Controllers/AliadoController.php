<?php

namespace App\Http\Controllers;

use App\User;
use App\Repsaliado;
use App\FileProcessor;
use Illuminate\Support\Str;
use App\Contracargosaliado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreAdminRequest;


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

        $cards = Contracargosaliado::get();

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
}