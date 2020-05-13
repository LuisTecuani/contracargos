<?php

namespace App\Http\Controllers;

use App\CellersBlacklist;
use App\CellersUser;
use App\ContracargosCellers;
use App\ContracargosCellersBanorte;
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

    public function storeChargedback()
    {
        $today = now()->format('Y-m-d');

        $banorte = ContracargosCellersBanorte::select('user_id','email')
            ->where('created_at', 'like', $today.'%');

        $chargedback = ContracargosCellers::select('user_id','email')
            ->where('created_at', 'like', $today.'%')
            ->union($banorte)
            ->get();

        foreach ($chargedback as $row) {

            $exist = CellersBlacklist::where('user_id', $row->user_id)->first();
            if (!$exist) {
                $bList = new CellersBlacklist();
                $bList->email = $row->email;
                $bList->user_id = $row->user_id ?? null;
                $bList->save();
            }
        }
        return back();
    }
}
