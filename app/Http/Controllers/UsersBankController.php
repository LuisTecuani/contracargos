<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UsersBankController extends Controller
{
    public function index()
    {
        $platforms = array_unique(Arr::pluck(config('platforms'), 'name'));

        return view("users-bank.index", compact('platforms'));
    }

    public function show(Request $request)
    {
        $platforms = array_unique(Arr::pluck(config('platforms'), 'name'));
        $platform = config('platforms.'.$request->platform);
        $results = $platform['reps_model']::with('bin')
            ->select('bin.bank', 'detalle_mensaje', DB::raw('count(*) as amount'))
            ->groupBy('detalle_mensaje')
            ->get();
dd($results);
        return view("users-bank.index", compact('platforms', 'results'));
    }
}
