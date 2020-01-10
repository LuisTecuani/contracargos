<?php

namespace App\Http\Controllers;

use App\MediakeyBlacklist;
use App\MediakeyUser;
use App\Http\Requests\StoreAdminRequest;

class MediakeyBlacklistController extends Controller
{
    public function index()
    {
        return view("mediakey.blacklist.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $emailsS = $request->input('emails');
        $emails = preg_split("[\r\n]", $emailsS);
        foreach ($emails as $email) {

            $exist = MediakeyBlacklist::where('email', $email)->first();
            if (!$exist) {
                $bList = new MediakeyBlacklist();
                $user = MediakeyUser::select('id')
                    ->where('email', $email)
                    ->first();
                $bList->email = $email;
                $bList->user_id = $user->id ?? null;
                $bList->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("mediakey.index");
    }
}
