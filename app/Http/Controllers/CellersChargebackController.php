<?php

namespace App\Http\Controllers;

use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;
use App\Mail\ChargebackEmail;
use Illuminate\Support\Facades\Mail;

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
        $query = ContracargosCellersBanorte::select('user_id')
            ->whereDate('created_at', today());

        $ids = ContracargosCellers::select('user_id')
            ->whereDate('created_at', today())
            ->union($query)
            ->groupBy("user_id")
            ->get();

        $this->sendEmail($ids);

        return view("cellers.chargeback.last", compact('ids'));
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

    public function sendEmail($users)
    {
        $data = new \stdClass();
        $data->subject = 'contracargos cellers';

        $data->users = $users->map(function ($user) {
            return $user->user_id;
        })->unique();

        Mail::to("danielcarrillo@thehiveteam.com")->send(new ChargebackEmail($data));
    }
}
