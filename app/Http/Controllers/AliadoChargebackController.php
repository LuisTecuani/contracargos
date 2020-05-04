<?php

namespace App\Http\Controllers;

use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
use App\FileProcessor;
use App\Http\Requests\StoreAdminRequest;
use http\Env\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

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

        $query = ContracargosAliadoBanorte::select('email', 'fecha_contracargo', 'fecha_consumo', 'tarjeta','autorizacion', 'created_at','user_id')
            ->whereDate('created_at', today());

        $cards = ContracargosAliado::select('email', 'fecha_contracargo', 'fecha_consumo', 'tarjeta','autorizacion', 'created_at','user_id')
            ->whereDate('created_at', today())
            ->union($query)->get();

        return view("aliado.chargeback.index", compact('cards'));
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

    public function show()
    {
        $query = ContracargosAliadoBanorte::createdToday();

        $emails = ContracargosAliado::select('email')
            ->whereDate('created_at', today())
            ->union($query)
            ->groupBy("email")
            ->get();

        return view("aliado.chargeback.last", compact('emails'));
    }

    public function storeImage(StoreAdminRequest $request)
    {
        $files = $request->file('files');
        foreach ($files as $file)
        {
        echo (new TesseractOCR($file))
            ->userPatterns('/Users/luisramos/code/contracargos/resources/patterns.txt')
            ->setOutputFile('/Users/luisramos/Downloads/searchable.txt')
            ->run();
        }
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
