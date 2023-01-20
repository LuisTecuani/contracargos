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
        $newEncrypter = new \Illuminate\Encryption\Encrypter( 'llaveDePrueba123');
        $plainTextToEncrypt = '1234567890123456';
        $encrypted = $newEncrypter->encrypt( $plainTextToEncrypt );
        $encrypted = "eyJpdiI6InlRWURKSHdLRVM5VEVMay9CR3FlTVE9PSIsInZhbHVlIjoiVHhVbmdDUTdEUHJHbjlEbHhCSWptc3Q0VkkzTzdyQldBZFM1Sm5uUXh2ND0iLCJtYWMiOiJlYTI3NzI5YTg1YmRkZjE3YzI2OGQxODJjZTgyMGJhNDRmZWFiMTQxM2Q4MTkxYjVlYjgxMmFjMmRiYjc1ZDY2In0=";
        $decrypted = $newEncrypter->decrypt( $encrypted );
//        dd($decrypted);
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

        return view("users-bank.index", compact('platforms', 'results'));
    }
}
