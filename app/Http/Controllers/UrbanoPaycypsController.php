<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrbanoPaycypsController extends Controller
{
    public function index()
    {
        return view("urbano.paycyps.index");
    }
}
