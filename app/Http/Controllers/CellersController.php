<?php

namespace App\Http\Controllers;

use App\CellersUser;
use App\User;
use App\Repscellers;
use Illuminate\Support\Str;
use App\ContracargosCellers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreAdminRequest;


class CellersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $role = User::role();

        DB::select('update contracargos_cellers c left join repscellers r on r.autorizacion=c.autorizacion 
                set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_cellers c join cellers.users u on u.id=c.user_id set c.email=u.email');

        $cards = ContracargosCellers::whereDate('created_at', today())->get();

        $cards2 = ContracargosCellers::get();

        return view("cellers.index", compact('cards', 'cards2', 'role'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->autorizacionSeisDigit($store[0]);
            $Contracargos = new ContracargosCellers();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("cellers.index");

    }

    public function store2(StoreUserRequest $request)
    {
        $Contracargos = new ContracargosCellers();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("cellers.index");
    }

    public function import(ImportRepRequest $request)
    {
        $files = $request->file('files');
        $total = count($files);
        Session()->flash('message', 'Reps Registrados: ' . $total);
        foreach ($files as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');
            $valid = DB::table('consultas.repscellers')
                ->where('source_file', 'like', $source)->get();
            if (count($valid) === 0) {
                $responses = processRep($file);

                foreach ($responses[1] as $row) {

                    Repscellers::create([
                        'tarjeta' => $row[0],
                        'estatus' => 'Aprobada',
                        'terminacion' => substr($row[0], -4, 4),
                        'user_id' => $row[1],
                        'fecha' => $row[2],
                        'autorizacion' => $row[5],
                        'monto' => $row[8],
                        'source_file' => $source
                    ]);
                }

                foreach ($responses[0] as $row) {

                    Repscellers::create([
                        'tarjeta' => $row[0],
                        'estatus' => 'Rechazada',
                        'user_id' => $row[1],
                        'fecha' => $row[2],
                        'terminacion' => substr($row[0], -4, 4),
                        'motivo_rechazo' => trim($row['motivo']),
                        'monto' => $row[count($row) - 4],
                        'source_file' => $source
                    ]);
                }
            }
        }
        return redirect()->route("cellers.index");
    }

    public function last()
    {
        $emails = DB::table("contracargos_cellers as cc")
            ->leftJoin("repscellers as rm", 'rm.autorizacion', '=', 'cc.autorizacion')
            ->leftJoin("cellers.users as u", 'u.id', '=', 'rm.user_id')
            ->select('email')
            ->select('u.email')
            ->whereDate('cc.created_at', today())
            ->whereColumn('rm.terminacion', 'cc.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->groupBy("u.email")
            ->get();

        return view("cellers.last", compact('emails'));
    }

    public function autorizacionSeisDigit($aut) {
        $len = strlen($aut);
        while($len < 6){
            $aut = "0$aut";
            $len = strlen($aut);
        }
        $autseisd = $aut;
        return $autseisd;
    }
}
