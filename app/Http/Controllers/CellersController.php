<?php

namespace App\Http\Controllers;

use App\CreditCards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CellersController extends Controller
{
    public function index() {

        if ($searched_card = [request('tarjetas')]) {


            $cards = CreditCards::with('user')->where('number', 'like', compact('searched_card'))->get();


        } else {
            $cards = CreditCards::with('user')->latest()->paginate(14);
        }

        return view('cellers.index', compact('cards'));
    }


    public function finder() {

        $cardsS =  request()->input('tarjetas');
        $cards = preg_split("[\r\n]",$cardsS);

        echo '"id","email","number","num_buscado"'."<br>";

        foreach($cards as $card) {

            $cas = DB::connection('mysql2')->table('tdc')
                ->leftJoin('users', 'tdc.user_id', '=', 'users.id')
                ->where('number', 'like', $card)->get();

            foreach($cas as $ca){
                echo $ca->user_id.',"';
                echo $ca->email.'",';
                echo $ca->number.','.$card."<br>";
            }}

        return view('cellers.index')->with(compact('cards'));
    }
}
