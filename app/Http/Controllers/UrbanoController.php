<?php

namespace App\Http\Controllers;

class UrbanoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("urbano.index");
    }

}
