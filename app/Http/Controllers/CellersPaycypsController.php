<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CellersPaycypsController extends Controller
{
    public function index()
    {
        return view("cellers.paycyps.index");
    }
}
