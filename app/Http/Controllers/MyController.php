<?php

namespace App\Http\Controllers;

use App\Imports\RepsmediakeyImport;
use App\Repsmediakey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Exports\RepsmediakeyExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class MyController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function importExportView()
    {
        return view('import');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new RepsmediakeyExport, 'repsmediakey.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import()
    {
        function fix_keys($array) {
            foreach ($array as $k => $val) {
                if (is_array($val))
                    $array[$k] = fix_keys($val); //recurse
            }
            return array_values($array);
        }



       $rep10 = file_get_contents(request()->file('file')) ;
        if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
         {
            $rep9 = Str::after($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS                        ');
            $rep8 = Str::before($rep9, 'Totales:                                                                           ');
            $rep7 = Arr::sort(preg_split("/\n/", $rep8));
            $rep6 = preg_grep("/([[:digit:]]{16})/", $rep7);

            foreach ($rep6 as $cls => $vls) {
                $rep5[$cls] = preg_grep("/\S/", preg_split("/\s/", $vls));
            }

            $rep4 = fix_keys($rep5);

            foreach ($rep4 as $rep3) {
                $rep3[10] = Str::after($rep3[10], 'C0000000');

                Repsmediakey::create([

                    'tarjeta' => $rep3[0],

                    'user_id' => $rep3[10],

                    'fecha' => $rep3[2],

                    'autorizacion' => $rep3[5],

                    'monto' => $rep3[8]

                ]);

            }


            return back();
            } else
        {
            return view('noaceptados');
        }
       /* dd($rep);



        return Excel::import(new RepsmediakeyImport, $rep);
       dd($rep);

        Excel::import(new UsersImport,request()->file('file'));

        return back();
       */
    }
}
