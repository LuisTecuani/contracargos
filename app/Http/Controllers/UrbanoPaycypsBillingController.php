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
        $file = $request->file('file');

        $fileName = $file->getClientOriginalName();

        $saved = UrbanoPaycypsBill::where('file_name', 'like', $fileName)->get();

        if (count($saved) === 0) {
            $import = (new UrbanoPaycypsImport())->fromRequest($fileName, $request->folio);

            Excel::import($import, $file);
        }

        return back();
    }

    public function update(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $deletedAt = $request->deleted_at;

        foreach ($cards as $card) {

            $entry = UrbanoPaycypsBill::where('tdc','like', $card)->get();

            foreach ($entry as $row) {
                $row->deleted_at = $deletedAt;
                $row->save();
            }
        }
        return back();
    }
}
