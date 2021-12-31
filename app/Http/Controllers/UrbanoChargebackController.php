<?php

namespace App\Http\Controllers;

use App\ContracargosUrbano;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;
use App\Mail\ChargebackEmail;
use Illuminate\Support\Facades\Mail;

class UrbanoChargebackController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }

    public function index()
    {
        $this->update();
        (New UrbanoBanorteChargebackController)->update();


        $cards = ContracargosUrbano::select('user_id','email','autorizacion', 'tarjeta', 'fecha_rep', 'created_at', 'updated_at')
            ->whereDate('created_at', today())
            ->get();

        return view("urbano.chargeback.index", compact('cards'));
    }


    public function show()
    {
        $ids = ContracargosUrbano::select('user_id')
            ->whereDate('created_at', today())
            ->groupBy("user_id")
            ->get();

        $this->sendEmail($ids);

        return view("urbano.chargeback.last", compact('ids'));
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $exist = ContracargosUrbano::where([['autorizacion', $store[0]],['tarjeta', $store[1]]])->first();
            if (! $exist) {
                $Contracargos = new ContracargosUrbano();
                $Contracargos->autorizacion = $store[0];
                $Contracargos->tarjeta = $store[1];
                $Contracargos->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("urbano.index");

    }

    public function update()
    {

        $contracargos = ContracargosUrbano::with('reps')
            ->whereNull('user_id')
            ->get();

        foreach ($contracargos as $contracargo) {
            foreach ($contracargo->reps as $rep) {
                if($contracargo->tarjeta == $rep->terminacion) {
                    ContracargosUrbano::where('id', $contracargo->id)
                        ->update([
                            'user_id' => $rep->user_id,
                            'fecha_rep' => $rep->fecha]);
                }
            }
        }

        $noEmails = ContracargosUrbano::with('user')
            ->whereNull('email')
            ->get();

        foreach ($noEmails as $row) {
            if($row->user) {
                ContracargosUrbano::where('id', $row->id)
                    ->update(['email' => $row->user->email]);
            }
        }

    }

    public function sendEmail($users)
    {
        $data = new \stdClass();
        $data->subject = 'contracargos urbano';

        $data->users = $users->map(function ($user) {
            return $user->user_id;
        })->unique();

        Mail::to("danielcarrillo@thehiveteam.com")->send(new ChargebackEmail($data));
    }
}
