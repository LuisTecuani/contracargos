<?php

namespace App\Http\Controllers;

use App\CellersBlacklist;
use App\CellersUser;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Support\Facades\DB;

class CellersBlacklistController extends Controller
{
    public function index()
    {
        $cards = ['547046%0699',
            '557907%5540',
            '557907%9310',
            '491573%5158',
            '547146%8332',
            '557910%8637',
            '491573%5471',
            '557909%0843',
            '547046%3268',
            '547146%7235',
            '547046%5369',
            '557910%8863',
            '547046%5155',
            '557910%5274',
            '557910%9478',
            '491573%9134',
            '547046%8836',
            '491573%1232',
            '547146%4231',
            '491573%8890',
            '557909%8548',
            '547046%8121',
            '547046%1267',
            '494133%4903',
            '491572%6967',
            '557909%7966',
            '547046%5537',
            '491573%3313',
            '557907%3779',
            '491573%7341',
            '547046%3629',
            '557910%1377',
            '557910%0122',
            '547046%3095',
            '547046%7778',
            '547046%1810',
            '547046%7059',
            '540845%2210',
            '557907%5812',
            '491573%0191',
            '491572%8798',
            '547146%9395',
            '547046%4546',
            '547046%3426',
            '540845%6874',
            '557910%7117',
            '557907%5253',
            '547046%1500',
            '547046%3613',
            '547046%5210',
            '557910%4688',
            '547046%9376',
            '491573%0621',
            '557909%5948',
            '491573%0965',
            '491573%6224',
            '491573%7547',
            '547046%7497',
            '557910%9330',
            '557910%9330',
            '557909%9032',
            '547046%3111',
            '557907%9417',
            '491573%3710',
            '491573%4448',
            '557909%7573',
            '491573%0509',
            '491573%0914',
            '547046%4395',
            '491573%0902',
            '557907%8789',
            '547046%9866',
            '554900%9656',
            '547046%8735',
            '540845%6865',
            '547146%8063',
            '557910%6087',
            '547046%1611',
            '491573%5261',
            '547046%4248',
            '547046%2984',
            '547046%2058',
            '547046%4217',
            '494133%2760'];
$users = [];
        foreach ($cards as $row => $card) {
            $c = DB::table('repsmediakey as ra')
                ->where([['ra.tarjeta', 'like', $card],
                    ['estatus', '=', 'Aprobada'],
                    ['fecha', 'like', '2019-12-3%']])
                ->select( 'ra.user_id as user_id', 'ra.tarjeta', 'fecha')
                ->get();
echo $c;
            $users[$row] = $c;
        }
        dd($users);
        return view("cellers.blacklist.index", compact('users'));
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
