<?php

namespace App\Http\Controllers;

use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AliadoChargebackController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        DB::select('update contracargos_aliado c left join repsaliado r on r.autorizacion=c.autorizacion
            set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_aliado c join aliado.users u on u.id=c.user_id set c.email=u.email');

        DB::select('update contracargos_aliado_banorte c left join respuestas_banorte_aliado r on r.autorizacion=c.autorizacion
            set c.user_id=r.user_id, c.fecha_rep=r.fecha where c.user_id is null and r.terminacion=c.tarjeta');

        DB::select('update contracargos_aliado_banorte c join aliado.users u on u.id=c.user_id set c.email=u.email');

        $query = ContracargosaliadoBanorte::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today());

        $cards = Contracargosaliado::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
        ->whereDate('created_at', today())
            ->union($query)->get();

        return view("aliado.chargeback.index", compact('cards'));
    }


    public function show()
    {
        $query = DB::table("contracargos_aliado_banorte as cc")
            ->leftJoin("respuestas_banorte_aliado as rm", 'rm.autorizacion', '=', 'cc.autorizacion')
            ->leftJoin("aliado.users as u", 'u.id', '=', 'rm.user_id')
            ->select('email')
            ->select('u.email')
            ->whereDate('cc.created_at', today())
            ->whereColumn('rm.terminacion', 'cc.tarjeta')
            ->orWhere('rm.autorizacion', null);

        $emails = DB::table("consultas.contracargos_aliado as cm")
            ->leftJoin("consultas.repsaliado as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("aliado.users as u", 'u.id', '=', 'rm.user_id')
            ->select('u.email')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->union($query)
            ->groupBy("u.email")
            ->get();

        return view("aliado.chargeback.last", compact('emails'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $exist = ContracargosAliado::where([['autorizacion', $store[0]],['tarjeta', $store[1]]])->first();
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


    public function storeTxt(Request $request)
    {
        $text = $request->input('text');

        $chargebackDate = $request->chargeback_date;

        $processedText = processTxt($text);
        $chargebacks = [];
        foreach ($processedText[0] as $index => $cont) {
            $chargebacks[$index]['authorization'] = $cont;
        }
        foreach ($processedText[1] as $index => $cont) {
            $chargebacks[$index]['card'] = $cont;
        }
        foreach ($processedText[2] as $index => $cont) {
            $chargebacks[$index]['date'] = $cont;
        }

        foreach ($chargebacks as $row) {
            $card = substr($row['card'], -4, 4);
            $exist = ContracargosAliadoBanorte::where([['autorizacion', $row['authorization']],['tarjeta', $card]])->first();
            if (! $exist) {
                $Contracargos = new ContracargosAliadoBanorte();
                $Contracargos->autorizacion = $row['authorization'];
                $Contracargos->tarjeta = $card;
                $Contracargos->fecha_consumo = $row['date'];
                $Contracargos->fecha_contracargo = $chargebackDate;
                $Contracargos->save();
            }
        }
        return back();
    }
}
