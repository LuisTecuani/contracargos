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
                    $user = (new AliadoUser)->findById($user_id);
                } else {
                    $user = (new AliadoUser)->findByEmail($request->email);
                }
                $banorte = (new RespuestasBanorteAliado)->getUserAcceptedCharges($user->id);

                $charges = (new Repsaliado)->getUserAcceptedCharges($user->id)->concat($banorte);

                break;
            case 'cellers':
                if ($user_id = $request->user_id) {
                    $user = (new CellersUser)->findById($user_id);
                } else {
                    $user = (new CellersUser)->findByEmail($request->email);
                }
                $banorte = (new RespuestasBanorteCellers)->getUserAcceptedCharges($user->id);

                $charges = (new Repscellers)->getUserAcceptedCharges($user->id)->concat($banorte);

                break;
            case 'mediakey':
                if ($user_id = $request->user_id) {
                    $user = (new MediakeyUser)->findById($user_id);
                } else {
                    $user = (new MediakeyUser)->findByEmail($request->email);
                }
                $banorte = (new RespuestasBanorteMediakey)->getUserAcceptedCharges($user->id);

                $charges = (new Repsmediakey)->getUserAcceptedCharges($user->id)->concat($banorte);

                break;
        }
        return view('find_user.show',compact('user','charges'));
    }
}
