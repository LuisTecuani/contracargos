<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\UrbanoBlacklist;
use App\UrbanoUser;
use Illuminate\Http\Request;

class UrbanoBlacklistController extends Controller
{
    public function index()
    {
        return view("urbano.blacklist.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $emails = preg_split("[\r\n]", $request->input('emails'));

        foreach ($emails as $email) {
            $exist = UrbanoBlacklist::where('email', $email)->first();

            if (!$exist) {
                $bList = new UrbanoBlacklist();
                $user = UrbanoUser::select('id')
                    ->where('email', $email)
                    ->first();
                $bList->email = $email;
                $bList->user_id = $user->id ?? null;
                $bList->save();
            } else {
                $exist->updated_at = now();
                $exist->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return back();
    }
}
