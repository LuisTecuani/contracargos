<?php

namespace App\Http\Controllers;

use App\UrbanoPaycypsBill;
use App\Imports\UrbanoPaycypsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UrbanoPaycypsBillingController extends Controller
{
    public function storeCsv(Request $request)
    {
        $files = $request->file('files');

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $saved = UrbanoPaycypsBill::where('file_name', 'like', $fileName)->get();

            if (count($saved) === 0) {
                $import = (new UrbanoPaycypsImport())->fromFile($fileName);

                Excel::import($import, $file);
            }
        }

        return back();
    }

    public function update(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $billingConfirmationDate = $request->bill_date;

        foreach ($cards as $card) {

            $entry = UrbanoPaycypsBill::where('tdc','like', $card)->get();

            foreach ($entry as $row) {
                $row->billing_confirmation_date = $billingConfirmationDate;
                $row->save();
            }
        }
        return back();
    }
}
