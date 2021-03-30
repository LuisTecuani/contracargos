<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\ThxBlacklist;
use App\ThxUser;
use Illuminate\Http\Request;

class ThxBlacklistController extends Controller
{
    public function index()
    {
        return view("thx.blacklist.index");
    }

    public function store(StoreAdminRequest $request)
    {
        $emails = preg_split("[\r\n]", $request->input('emails'));

        foreach ($emails as $email) {
            $exist = ThxBlacklist::where('email', $email)->first();

            if (!$exist) {
                $bList = new ThxBlacklist();
                $user = ThxUser::select('id')
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
