<?php

namespace App\Http\Controllers;

use App\UrbanoPaycypsBill;
use App\ContracargosUrbanoPaycyps;
use Illuminate\Http\Request;

class UrbanoPaycypsChargebackController extends Controller
{
    public function store(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $chargebackDate = $request->chargeback_date;

        foreach ($cards as $card) {
            $cargo = UrbanoPaycypsBill::where('tdc','like', $card)->get();

            foreach ($cargo as $row) {
                $Contracargos = new ContracargosUrbanoPaycyps();
                $Contracargos->user_id = $row->user_id;
                $Contracargos->tdc = $row->tdc;
                $Contracargos->file_name = $row->file_name;
                $Contracargos->chargeback_date = $chargebackDate;
                $Contracargos->save();
            }
        }
        return back();
    }
}
