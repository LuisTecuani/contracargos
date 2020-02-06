<?php

namespace App\Http\Controllers;

use App\Bin;
use App\Http\Requests\StoreAdminRequest;
use App\Repsaliado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BinsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bins = Bin::select('bank', 'network', 'country', 'bin')
            ->get();

        return view("bins.index", compact('bins'));
    }

    public function store(StoreAdminRequest $request)
    {
        $data = $request->input('data');
        $arr = preg_split("[\r\n]", $data);
        foreach ($arr as $a) {
            $store = preg_split("[,]", $a);
            $exist = Bin::where('bin', $store[2])->first();
            if (! $exist) {
                $bin = new Bin();
                $bin->bank = $store[0];
                $bin->network = $store[1];
                $bin->country = $store[2];
                $bin->bin = $store[3];
                $bin->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("tools.index");
    }

    public function show()
    {
        $tdc = DB::table('billing.charges')
            ->selectRaw('left(tdc, 6) as d')
            ->groupBy('tdc');

        $rejected = DB::table('billing.charges')
            ->selectRaw('left(tdc, 6) as b, count(*) as c')
            ->where('status', 'not like', 'paid')
            ->groupBy('b');

        $aproved = DB::table('billing.charges')
            ->selectRaw('left(tdc, 6) as b, count(*) as a')
            ->where('status', 'like', 'paid')
            ->groupBy('b');

        $bins = Bin::selectRaw('d as bin, bank, country, network, ifnull(a,0) as aproved, ifnull(c,0) as rejected, c/(c+a)*100 as percent')
            ->rightJoinSub($tdc, 'tdc', function ($join){
                $join->on('bins.bin', '=', 'tdc.d');
            })
            ->leftJoinSub($aproved, 'aproved', function ($join){
                $join->on('bins.bin', '=', 'aproved.b');
            })
            ->leftJoinSub($rejected, 'rejected', function ($join){
                $join->on('bins.bin', '=', 'rejected.b');
            })
            ->orderBy('rejected', 'desc')
            ->take(5)
            ->get();

        return view("bins.show", compact('bins'));
    }

}
