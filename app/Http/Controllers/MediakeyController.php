<?php

namespace App\Http\Controllers;

use App\Repsmediakey;
use App\FileProcessor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        $role = DB::table('consultas.users as u')
            ->select('u.role')
            ->where('u.id', '=', Auth::id())
            ->get();

        $cards = DB::table("consultas.contracargos_mediakey as cm")
            ->leftJoin("consultas.repsmediakey as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("mediakey.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->orderBy('cm.id')
            ->get();

        $cards2 = DB::table("consultas.contracargos_mediakey as cm")
            ->leftJoin("consultas.repsmediakey as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("mediakey.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orderBy('cm.id')
            ->get();

        return view("$this->database.index", compact('cards', 'cards2', 'role'));
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
        $archivos = $request->file('files');
        $total = count($archivos);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            if (substr($source, -4, 4) != 6873) {
                Session()->flash('message1', 'Verifique que el Archivo Rep Corresponda');
                return back();
            } else {
                $valid = DB::table('consultas.repsmediakey as ra')
                    ->where('source_file', 'like', $source)->get();
                if (count($valid) === 0) {
                    $rep10 = file_get_contents($file);

                    if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS')) {
                        $rep4 = accepRepToArray($rep10);

                        foreach ($rep4 as $rep3) {

                            $rep3[10] = Str::after($rep3[10], 'C0000000');

                            Repsmediakey::create([

                                'tarjeta' => $rep3[0],

                                'terminacion' => substr($rep3[0], -4, 4),

                                'user_id' => $rep3[10],

                                'fecha' => $rep3[2],

                                'autorizacion' => $rep3[5],

                                'monto' => $rep3[8],

                                'source_file' => $source

                            ]);

                        }
                    }
                }
            }
            Session()->flash('message', 'Reps Registrados: ' . $total);

            return redirect()->route("$this->database.index");
        }

    }
}


