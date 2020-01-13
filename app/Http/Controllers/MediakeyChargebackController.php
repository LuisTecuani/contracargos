<?php

namespace App\Http\Controllers;

use App\ContracargosMediakey;
use App\ContracargosMediakeyBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediakeyChargebackController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        $this->update();
        (New MediakeyBanorteChargebackController)->update();

        $query = ContracargosMediakeyBanorte::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today());

        $cards = ContracargosMediakey::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
        ->whereDate('created_at', today())
            ->union($query)->get();

        return view("mediakey.chargeback.index", compact('cards'));
    }


    public function show()
    {
        $query = DB::table("contracargos_mediakey_banorte as cc")
            ->leftJoin("respuestas_banorte_mediakey as rm", 'rm.autorizacion', '=', 'cc.autorizacion')
            ->leftJoin("mediakey.users as u", 'u.id', '=', 'rm.user_id')
            ->select('email')
            ->select('u.email')
            ->whereDate('cc.created_at', today())
            ->whereColumn('rm.terminacion', 'cc.tarjeta')
            ->orWhere('rm.autorizacion', null);

        $emails = DB::table("consultas.contracargos_mediakey as cm")
            ->leftJoin("consultas.repsmediakey as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("mediakey.users as u", 'u.id', '=', 'rm.user_id')
            ->select('u.email')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->union($query)
            ->groupBy("u.email")
            ->get();

        return view("mediakey.chargeback.last", compact('emails'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $exist = ContracargosMediakey::where([['autorizacion', $store[0]],['tarjeta', $store[1]]])->first();
            if (! $exist) {
                $Contracargos = new ContracargosMediakey();
                $Contracargos->autorizacion = $store[0];
                $Contracargos->tarjeta = $store[1];
                $Contracargos->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("mediakey.index");

    }

    public function update()
    {

        $contracargos = ContracargosMediakey::with('reps')
            ->whereNull('user_id')
            ->get();

        foreach ($contracargos as $contracargo) {
            foreach ($contracargo->reps as $rep) {
                if($contracargo->tarjeta == $rep->terminacion) {
                    ContracargosMediakey::where('id', $contracargo->id)
                        ->update([
                            'user_id' => $rep->user_id,
                            'fecha_rep' => $rep->fecha]);
                }
            }
        }

        $noEmails = ContracargosMediakey::with('user')

            ->first();
dd($noEmails);
        foreach ($noEmails as $row) {
            if($row->user) {
                ContracargosMediakey::where('id', $row->id)
                    ->update(['email' => $row->user->email]);
            }
        }

    }
}
