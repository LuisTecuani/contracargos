<?php

namespace App\Http\Controllers;

use App\CreditCards;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\DB;
use App\Providers\BroadcastServiceProvider;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class mediakey extends Controller
{

    public function index() {

        if ($searched_card = [request('tarjetas')]) {


        $cards = CreditCards::with('user')->where('number', 'like', compact('searched_card'))->get();


        } else {
            $cards = CreditCards::with('user')->latest()->paginate(14);
        }

        return view('mediakey.index', compact('cards'));
    }

    public function show()
    {

        $cardsA = [
            '415231%0840',
            '549949%8516',
            '549949%8516',
            '549949%8516',
            '415231%1570',
            '415231%7748',
            '547046%4570',
            '493172%3614',
            '528843%3700',
            '426807%6665',
            '426807%6665',
            '415231%0667',
            '415231%4658',
            '415231%1875',
            '491573%3115',
            '415231%1570',
            '415231%1570'
        ];


        echo '"id","email","number","num_buscado"'."<br>";
        foreach($cardsA as $card) {

            $cards = CreditCards::with('user')->where('number', 'like', compact('card'))->get();
            foreach($cards as $ca){
                echo $ca->user_id.',"';
                echo $ca->user->email.'",';
                echo $ca->number.','.$card."<br>";
        }}
        return (new UsersExport)->download('users.xlsx');
    }

    public function finder() {


            $autorizacionesS =  request()->input('autorizaciones');

            $autorizacionesRaw = preg_split("[\r\n]",$autorizacionesS);

            echo '"num_buscado","autorizacion","fecha","number","user_id","email"'."<br>";

            foreach($autorizacionesRaw as $autorizacionRaw) {
                if(strlen($autorizacionRaw) == 1)
                {
                    $autorizacion = "00000$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 2)
                {
                    $autorizacion = "0000$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 3)
                {
                    $autorizacion = "000$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 4)
                {
                    $autorizacion = "00$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 5)
                {
                    $autorizacion = "0$autorizacionRaw";
                }

                if(strlen($autorizacionRaw) == 6)
                {
                    $autorizacion = "$autorizacionRaw";
                }

                $cards = DB::table('consultas.repsmediakey as rm')
                    ->leftjoin('mediakey.users as u', 'u.id', '=', 'rm.user_id')
                    ->leftjoin('mediakey.credit_cards as cc', 'u.id', '=', 'cc.user_id')
                    ->where('rm.autorizacion', '=', $autorizacion)
                    ->get();

                foreach($cards as $ca)
                {

                    echo $autorizacion.',"';
                    echo $ca->autorizacion.'","';
                    echo $ca->fecha.'","';
                    echo $ca->number.'",';
                    echo $ca->id.',';
                    echo $ca->email."<br>";
                }}


            return view('mediakey.index')->with(compact('cards'));



        }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}


