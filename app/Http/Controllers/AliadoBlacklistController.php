<?php

namespace App\Http\Controllers;

use App\AliadoBlacklist;
use App\AliadoUser;
use App\Http\Requests\StoreAdminRequest;

class AliadoBlacklistController extends Controller
{
    public function index()
    {
        return view("aliado.blacklist.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $emailsS = $request->input('emails');
        $emails = preg_split("[\r\n]", $emailsS);
        foreach ($emails as $email) {

            $exist = AliadoBlacklist::where('email', $email)->first();
            if (!$exist) {
                $bList = new AliadoBlacklist();
                $user = AliadoUser::select('id')
                    ->where('email', $email)
                    ->first();
                $bList->email = $email;
                $bList->user_id = $user->id ?? null;
                $bList->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return back();
    }
}
