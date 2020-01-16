<?php

namespace App\Http\Controllers;

use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;

class AliadoChargebackController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        $this->update();
        (New AliadoBanorteChargebackController)->update();

        $query = ContracargosAliadoBanorte::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today());

        $cards = ContracargosAliado::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today())
            ->union($query)->get();

        return view("aliado.chargeback.index", compact('cards'));
    }


    public function show()
    {
        $query = ContracargosAliadoBanorte::select('email')
            ->whereDate('created_at', today());

        $emails = ContracargosAliado::select('email')
            ->whereDate('created_at', today())
            ->union($query)
            ->groupBy("email")
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

    public function update()
    {

        $contracargos = ContracargosAliado::with('reps')
            ->whereNull('user_id')
            ->get();

        foreach ($contracargos as $contracargo) {
            foreach ($contracargo->reps as $rep) {
                if($contracargo->tarjeta == $rep->terminacion) {
                    ContracargosAliado::where('id', $contracargo->id)
                        ->update([
                            'user_id' => $rep->user_id,
                            'fecha_rep' => $rep->fecha]);
                }
            }
        }

        $noEmails = ContracargosAliado::with('user')
            ->whereNull('email')
            ->get();

        foreach ($noEmails as $row) {
            if($row->user) {
                ContracargosAliado::where('id', $row->id)
                    ->update(['email' => $row->user->email]);
            }
        }

    }
}
