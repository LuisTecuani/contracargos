<?php

namespace App\Http\Controllers;

use App\User;
use App\Repsasmas;
use App\FileProcessor;
use App\ContracargosAsmas;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ImportRepRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreAdminRequest;

class AsmasController extends Controller
{
    public function __construct(FileProcessor $filep)
    {
        $this->middleware('auth');
        $this->fileP = $filep;
    }


    public function index()
    {
        $date = DB::table('aliado.user_tdc as ut')
            ->selectRaw('user_id, ut.exp_month, ut.exp_year, concat(ut.exp_month, ut.exp_year) as date, 
            date_format(str_to_date(concat(trim(trailing right(concat(ut.exp_month, right(ut.exp_year,2)), 2) from concat(ut.exp_month, right(ut.exp_year,2))),\'/\',right(concat(ut.exp_month, right(ut.exp_year,2)), 2)),"%m/%y"), "%m/%y") as i')
            ->whereIn('user_id',  ["195770",
"195794",
"283602",
"283606",
"195790",
"195795",
"284254",
"283691",
"195471",
"195784",
"283821",
"283182",
"195481",
"195488",
"284161",
"283174",
"195782",
"195789",
"195489",
"207948",
"289635",
"290567",
"209474",
"123926",
"124180",
"126234",
"128096",
"214808",
"287410",
"206473",
"195825",
"195524",
"289633",
"122900",
"124690",
"292784",
"125202",
"293442",
"289940",
"123158",
"210962",
"211265",
"294386",
"126740",
"127568",
"128357",
"130819",
"131115",
"132121",
"133497",
"135585",
"222646"])
            ->limit(50)
            ->get()->map(function ($item) {
                return new Carbon($item->date);
            });
        $role = User::role();

        $cards = DB::table("consultas.contracargos_asmas as cm")
            ->leftJoin("consultas.repsasmas as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("asmas.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orWhere('rm.autorizacion', null)
            ->orderBy('cm.id')
            ->get();
dd($date);

        $cards2 = DB::table("consultas.contracargos_asmas as cm")
            ->leftJoin("consultas.repsasmas as rm", 'rm.autorizacion', '=', 'cm.autorizacion')
            ->leftJoin("asmas.users as u", 'u.id', '=', 'rm.user_id')
            ->select('rm.user_id as user_id', 'u.email as email', 'rm.fecha as fecha', 'rm.tarjeta as t1', 'cm.tarjeta as t2',
                'cm.autorizacion as aut2', 'rm.autorizacion as aut1', 'cm.created_at as creacion')
            ->whereDate('cm.created_at', today())
            ->whereColumn('rm.terminacion', 'cm.tarjeta')
            ->orderBy('cm.id')
            ->get();

        return view("asmas.index", compact('cards', 'cards2', 'role'));
        return view("asmas.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $autorizacionesS = $request->input('autorizaciones');
        $arr = preg_split("[\r\n]", $autorizacionesS);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $store[0] = $this->fileP->autorizacionSeisDigit($store[0]);
            $Contracargos = new ContracargosAsmas();
            $Contracargos->autorizacion = $store[0];
            $Contracargos->tarjeta = $store[1];
            $Contracargos->save();
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("asmas.index");

    }

    public function store2(StoreUserRequest $request)
    {
        $Contracargos = new ContracargosAsmas();
        $Contracargos->autorizacion = $request->input('autorizacion');
        $Contracargos->tarjeta = $request->input('terminacion');
        $Contracargos->save();

        return redirect()->route("asmas.index");
    }

    public function import(ImportRepRequest $request)
    {
        $archivos = $request->file('files');
        $total = count($archivos);
        Session()->flash('message', 'Reps Registrados: ' . $total);

        foreach ($archivos as $file) {
            $source = Str::before($file->getClientOriginalName(), '.');

            $valid = DB::table('consultas.reps_aliado_rechazados')
                ->where('source_file', 'like', $source)->get();

            if (count($valid) === 0) {
                    $rep4 = processRep($file);

                    foreach ($rep4 as $rep3) {


                        Repsasmas::create([

                            'tarjeta' => $rep3[0],

                            'user_id' => $rep3[1],

                            'fecha' => $rep3[2],

                            'terminacion' => substr($rep3[0], -4, 4),

                            'autorizacion' => $rep3[5],

                            'monto' => $rep3[8],

                            'source_file' => $source

                        ]);
                    }
            }
        }

        return back();

    }

}
