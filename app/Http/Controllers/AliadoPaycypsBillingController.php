<?php

namespace App\Http\Controllers;

use App\AliadoPaycypsBill;
use App\Imports\AliadoPaycypsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AliadoPaycypsBillingController extends Controller
{
    public function storeCsv(Request $request)
    {
        $file = $request->file('file');

        $fileName = $file->getClientOriginalName();

        $saved = AliadoPaycypsBill::where('file_name', 'like', $fileName)->get();

        if (count($saved) === 0) {
            $import = (new AliadoPaycypsImport())->fromRequest($fileName, $request->folio);

            Excel::import($import, $file);
        }

        return back();
    }

    public function update(Request $request)
    {
        $cards = preg_split("[\r\n]",$request->input('cards'));

        $deletedAt = $request->deleted_at;

        foreach ($cards as $card) {

            $entry = AliadoPaycypsBill::where('tdc','like', $card)->get();

            foreach ($entry as $row) {
                $row->deleted_at = $deletedAt;
                $row->save();
            }
        }
        return back();
    }
}
