<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThxPaycypsController extends Controller
{
    public function index()
    {
        return view("thx.paycyps.index");
    }
}
