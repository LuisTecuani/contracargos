<?php

namespace App\Http\Controllers;

use App\ContracargosThxPaycyps;
use App\ThxPaycypsBill;
use Illuminate\Http\Request;

class ThxPaycypsChargebackController extends Controller
{
    public function store(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $chargebackDate = $request->chargeback_date;

        foreach ($cards as $card) {

            $cargo = (new ThxPaycypsBill)->getByTdc($card);

            foreach ($cargo as $row) {
                $Contracargos = new ContracargosThxPaycyps();
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
