<?php

namespace App\Http\Controllers;

use App\AliadoPaycypsBill;
use App\ContracargosAliadoPaycyps;
use Illuminate\Http\Request;

class AliadoPaycypsChargebackController extends Controller
{
    public function store(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));
dd($cards);
        $chargebackDate = $request->chargeback_date;

        foreach ($cards as $card) {

            $cargo = AliadoPaycypsBill::where('tdc','like', $card)->get();

            foreach ($cargo as $row) {
                $Contracargos = new ContracargosAliadoPaycyps();
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
