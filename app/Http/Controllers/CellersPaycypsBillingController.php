<?php

namespace App\Http\Controllers;

use App\CellersPaycypsBill;
use App\Imports\CellersPaycypsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CellersPaycypsBillingController extends Controller
{
    public function storeCsv(Request $request)
    {
        $file = $request->file('file');

        $fileName = $file->getClientOriginalName();

        $saved = CellersPaycypsBill::where('file_name', 'like', $fileName)->get();

        if (count($saved) === 0) {
            $import = (new CellersPaycypsImport())->fromRequest($fileName, $request->folio);

            Excel::import($import, $file);
        }


        return back();
    }

    public function update(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $billingConfirmationDate = $request->bill_date;

        foreach ($cards as $card) {

            $entry = CellersPaycypsBill::where('tdc','like', $card)->get();

            foreach ($entry as $row) {
                $row->billing_confirmation_date = $billingConfirmationDate;
                $row->save();
            }
        }
        return back();
    }
}
