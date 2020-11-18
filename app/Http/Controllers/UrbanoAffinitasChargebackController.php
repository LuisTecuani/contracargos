<?php

namespace App\Http\Controllers;

use App\UrbanoAffinitas;
use App\ContracargosUrbanoAffinitas;
use Illuminate\Http\Request;

class UrbanoAffinitasChargebackController extends Controller
{
    public function store(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $chargebackDate = $request->chargeback_date;

        foreach ($cards as $card) {

            $cargo = UrbanoAffinitas::where('card_number','like', $card)->get();
            foreach ($cargo as $row) {
                $Contracargos = new ContracargosUrbanoAffinitas();
                $Contracargos->email = $row->EMAIL;
                $Contracargos->tdc = $row->CARD_NUMBER;
                $Contracargos->chargeback_date = $chargebackDate;
                $Contracargos->save();
            }
        }
        return back();
    }
}
