<?php

namespace App\Http\Controllers;

use App\ContracargosMediakey;
use App\ContracargosMediakeyBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;

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
        $query = ContracargosMediakeyBanorte::select('email')
            ->whereDate('created_at', today());

        $emails = ContracargosMediakey::select('email')
            ->whereDate('created_at', today())
            ->union($query)
            ->groupBy("email")
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
            ->whereNull('email')
            ->get();

        foreach ($noEmails as $row) {
            if($row->user) {
                ContracargosMediakey::where('id', $row->id)
                    ->update(['email' => $row->user->email]);
            }
        }

    }
}
