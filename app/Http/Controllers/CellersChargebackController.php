<?php

namespace App\Http\Controllers;

use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Support\Facades\DB;

class CellersChargebackController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        $this->update();
        (New CellersBanorteChargebackController)->update();

        $query = ContracargosCellersBanorte::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today());

        $cards = ContracargosCellers::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today())
            ->union($query)->get();

        return view("cellers.chargeback.index", compact('cards'));
    }


    public function show()
    {
        $query = DB::table("contracargos_cellers_banorte as cc")
            ->leftJoin("respuestas_banorte_cellers as rm", 'rm.autorizacion', '=', 'cc.autorizacion')
            ->leftJoin("cellers.users as u", 'u.id', '=', 'rm.user_id')
            ->select('email')
            ->select('u.email')
            ->whereDate('cc.created_at', today())
            ->whereColumn('rm.terminacion', 'cc.tarjeta')
            ->orWhere('rm.autorizacion', null);

        $emails = DB::table("consultas.contracargos_cellers as cm")
            ->leftJoin("consultas.repscellers as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("cellers.users as u", 'u.id', '=', 'rm.user_id')
            ->select('u.email')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->union($query)
            ->groupBy("u.email")
            ->get();

        return view("cellers.chargeback.last", compact('emails'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $exist = ContracargosCellers::where([['autorizacion', $store[0]],['tarjeta', $store[1]]])->first();
            if (! $exist) {
                $Contracargos = new ContracargosCellers();
                $Contracargos->autorizacion = $store[0];
                $Contracargos->tarjeta = $store[1];
                $Contracargos->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("cellers.index");

    }

    public function update()
    {

        $contracargos = ContracargosCellers::with('reps')
            ->whereNull('user_id')
            ->get();

        foreach ($contracargos as $contracargo) {
            foreach ($contracargo->reps as $rep) {
                if($contracargo->tarjeta == $rep->terminacion) {
                    ContracargosCellers::where('id', $contracargo->id)
                        ->update([
                            'user_id' => $rep->user_id,
                            'fecha_rep' => $rep->fecha]);
                }
            }
        }

        $noEmails = ContracargosCellers::with('user')
            ->whereNull('email')
            ->get();

        foreach ($noEmails as $row) {
            if($row->user) {
                ContracargosCellers::where('id', $row->id)
                    ->update(['email' => $row->user->email]);
            }
        }

    }
}
