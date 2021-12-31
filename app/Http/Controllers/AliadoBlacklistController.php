<?php

namespace App\Http\Controllers;

use App\AliadoBlacklist;
use App\AliadoUser;
use App\ContracargosAliado;
use App\ContracargosAliadoBanorte;
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
            } else {
                $exist->updated_at = now();
                $exist->save();
            }
        }
        Session()->flash('message', 'Datos Registrados');

        return back();
    }

    public function storeChargedback()
    {
        $today = now()->format('Y-m-d');

        $banorte = ContracargosAliadoBanorte::select('user_id','email')
            ->where('created_at', 'like', $today.'%');

        $chargedback = ContracargosAliado::select('user_id','email')
            ->where('created_at', 'like', $today.'%')
            ->whereNotNull('email')
            ->union($banorte)
            ->get();

        foreach ($chargedback as $row) {

            $exist = AliadoBlacklist::where('user_id', $row->user_id)->first();
            if (!$exist) {
                $bList = new AliadoBlacklist();
                $bList->email = $row->email;
                $bList->user_id = $row->user_id ?? null;
                $bList->save();
            } else {
                $exist->updated_at = now();
                $exist->save();
            }
        }
        return back();
    }
}
