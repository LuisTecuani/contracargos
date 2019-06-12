<?php

namespace App\Http\Controllers;

use App\CreditCards;
use App\Repsmediakey;
use App\Exports\UsersExport;
use App\ContracargosMediakey;
use Illuminate\Support\Facades\DB;
use App\Providers\BroadcastServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;


class MediakeyController extends Controller
{

    public function index() {


        $cards = DB::table('consultas.contracargos_mediakey as cm')
                ->leftJoin('consultas.repsmediakey as rm','rm.autorizacion','=','cm.autorizacion')
                ->leftJoin('mediakey.users as u','u.id','=','rm.user_id')
                ->select('rm.user_id as user_id','u.email as email','rm.fecha as fecha','rm.tarjeta as t1','cm.tarjeta as t2',
                    'cm.autorizacion as aut2', 'rm.autorizacion as aut1','cm.created_at as creacion')
                ->whereColumn('rm.terminacion','cm.tarjeta')
                ->orWhere('rm.autorizacion',null)
                ->orderBy('cm.id')
                ->paginate(14);

        return view('mediakey.index',compact('cards'));
    }

    public function store(Request $request){
        $request->validate([
            'autorizaciones' => 'regex:/[0-9]{6}[[:punct:]][0-9]{4}\r\n[0-9]{6}[[:punct:]][0-9]{4}/i',
        ]);
        $autorizacionesS = $request->input('autorizaciones');
            $arr = preg_split("[\r\n]", $autorizacionesS);
            foreach ($arr as $a) {
                $store = preg_split("[,]", $a);
                $ContracargosMediakey = new ContracargosMediakey;
                $ContracargosMediakey->autorizacion = $store[0];
                $ContracargosMediakey->tarjeta = $store[1];
                $ContracargosMediakey->save();
            }
            return redirect()->route('mediakey.index');

        }

    

    public function import(Request $request)
    {
        function fix_keys($array) {
            foreach ($array as $k => $val) {
                if (is_array($val))
                    $array[$k] = fix_keys($val); //recurse
            }
            return array_values($array);
        }

        $archivos     =   $request->file('files');

        foreach($archivos as $file) {


            $rep10 = file_get_contents($file);


            if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS')) {
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


            }
        }
        return back();
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}


