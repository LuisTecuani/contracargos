<?php

namespace App\Http\Controllers;

use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;

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
        $query = ContracargosCellersBanorte::select('email')
            ->whereDate('created_at', today());

        $emails = ContracargosCellers::select('email')
            ->whereDate('created_at', today())
            ->union($query)
            ->groupBy("email")
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
