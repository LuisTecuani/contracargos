<?php

namespace App\Http\Controllers;

use App\AliadoUser;
use App\CellersUser;
use App\MediakeyUser;
use App\Repsaliado;
use App\Repscellers;
use App\Repsmediakey;
use App\RespuestasBanorteAliado;
use App\RespuestasBanorteCellers;
use App\RespuestasBanorteMediakey;
use Illuminate\Http\Request;

class FindUserController extends Controller
{
    public function index()
    {
        return view("find_user.index");
    }

    public function show(Request $request)
    {
        $platform = $request->platform;

        switch ($platform) {
            case 'aliado':
                if ($user_id = $request->user_id) {
                    $user = AliadoUser::select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
                        ->where('id','=',$user_id)
                        ->first();
                } else {
                    $user = AliadoUser::select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
                        ->where('email','=',$request->email)
                        ->first();
                }
                $banorte = RespuestasBanorteAliado::select('fecha', 'tarjeta')
                    ->where([['user_id','=',$user->id],['estatus','=','Aprobada']])
                    ->get();
                $charges = Repsaliado::select('fecha', 'tarjeta')
                    ->where([['user_id','=',$user->id],['estatus','=','Aprobada']])
                    ->get()->concat($banorte);
                break;
            case 'cellers':
                if ($user_id = $request->user_id) {
                    $user = CellersUser::select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
                        ->where('id','=',$user_id)
                        ->first();
                } else {
                    $user = CellersUser::select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
                        ->where('email','=',$request->email)
                        ->first();
                }
                $banorte = RespuestasBanorteCellers::select('fecha', 'tarjeta')
                    ->where([['user_id','=',$user->id],['estatus','=','Aprobada']])
                    ->get();
                $charges = Repscellers::select('fecha', 'tarjeta')
                    ->where([['user_id','=',$user->id],['estatus','=','Aprobada']])
                    ->get()->concat($banorte);
                break;
            case 'mediakey':
                if ($user_id = $request->user_id) {
                    $user = MediakeyUser::select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
                        ->where('id','=',$user_id)
                        ->first();
                } else {
                    $user = MediakeyUser::select('id', 'email','name','deleted_at as cancelled_at', 'created_at')
                        ->where('email','=',$request->email)
                        ->first();
                }
                $banorte = RespuestasBanorteMediakey::select('fecha', 'tarjeta')
                    ->where([['user_id','=',$user->id],['estatus','=','Aprobada']])
                    ->get();
                $charges = Repsmediakey::select('fecha', 'tarjeta')
                    ->where([['user_id','=',$user->id],['estatus','=','Aprobada']])
                    ->get()->concat($banorte);
                break;
        }
        return view('find_user.show',compact('user','charges'));
    }
}
