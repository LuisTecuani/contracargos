<?php

namespace App\Http\Controllers;

use App\ContracargosAliadoBanorte;
use Illuminate\Http\Request;

class AliadoBanorteChargebackController extends Controller
{
    public function store(Request $request)
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

    public function update()
    {
        $contracargos = ContracargosAliadoBanorte::with('reps')
            ->whereNull('user_id')
            ->get();

        foreach ($contracargos as $contracargo) {
            foreach ($contracargo->reps as $rep) {
                if($contracargo->tarjeta == $rep->terminacion) {
                    ContracargosAliadoBanorte::where('id', $contracargo->id)
                        ->update([
                            'user_id' => $rep->user_id,
                            'fecha_rep' => $rep->fecha]);
                }
            }
        }

        $noEmails = ContracargosAliadoBanorte::with('user')
            ->whereNull('email')
            ->get();

        foreach ($noEmails as $row) {
            if($row->user) {
                ContracargosAliadoBanorte::where('id', $row->id)
                    ->update(['email' => $row->user->email]);
            }
        }
    }
}
