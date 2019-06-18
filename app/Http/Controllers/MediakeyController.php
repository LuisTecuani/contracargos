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
                //->orderBy('cm.id')
                ->whereColumn('rm.terminacion','cm.terminacion')
                ->paginate(16);


        return view('mediakey.index',compact('cards'));
    }

    public function store(){
        //DB::table('contracargos_mediakey')->truncate();
        $autorizacionesS = request()->input('autorizaciones');
        if (preg_match("/[0-9][[:punct:]][0-9]/",$autorizacionesS)) {
            $arr = preg_split("[\r\n]", $autorizacionesS);
            foreach ($arr as $a) {
                $store = preg_split("[,]", $a);
                $ContracargosMediakey = new ContracargosMediakey;
                $ContracargosMediakey->autorizacion = $store[0];
                $ContracargosMediakey->tarjeta = $store[1];
                $ContracargosMediakey->terminacion = substr($store[1],-4,4);
                $ContracargosMediakey->save();
            }
            return redirect()->route('mediakey.index');
        }
        else {
            return redirect()->route('mediakey.index');

        }
        }

    

    public function import(Request $request)
    {

        $archivos     =   $request->file('files');

        foreach($archivos as $file)
        {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.repsmediakey as ra')
                ->where( 'source_file', 'like', $source)->get();

            if (count($valid) === 0)
            {
                $rep10 = file_get_contents($file);

                if (Str::contains($rep10, 'REPORTE DETALLADO DE TRANSACCIONES ACEPTADAS'))
                {
                    $rep4 = accep_rep_to_array($rep10);

                foreach ($rep4 as $rep3) {
                    $rep3[10] = Str::after($rep3[10], 'C0000000');

                    Repsmediakey::create([

                        'tarjeta' => $rep3[0],

                        'terminacion' => substr($rep3[0],-4,4),

                        'user_id' => $rep3[10],

                        'fecha' => $rep3[2],

                        'autorizacion' => $rep3[5],

                        'monto' => $rep3[8],

                        'source_file' => $source

                    ]);
                }
            }
        }}
        return back();
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}


