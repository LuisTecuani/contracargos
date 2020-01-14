<?php

namespace App\Http\Controllers;

class CellersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("cellers.index");
    }
}
