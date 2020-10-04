<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliadoPaycypsController extends Controller
{
    public function index()
    {
        return view("aliado.paycyps.index");
    }
}
