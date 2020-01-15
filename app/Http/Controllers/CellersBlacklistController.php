<?php

namespace App\Http\Controllers;

use App\CellersBlacklist;
use App\CellersUser;
use App\Http\Requests\StoreAdminRequest;

class CellersBlacklistController extends Controller
{
    public function index()
    {
        return view("cellers.blacklist.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $emailsS = $request->input('emails');
        $emails = preg_split("[\r\n]", $emailsS);
        foreach ($emails as $email) {

            $exist = CellersBlacklist::where('email', $email)->first();
            if (!$exist) {
                $bList = new CellersBlacklist();
                $user = CellersUser::select('id')
                    ->where('email', $email)
                    ->first();
                $bList->email = $email;
                $bList->user_id = $user->id ?? null;
                $bList->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return redirect()->route("cellers.index");
    }
}
