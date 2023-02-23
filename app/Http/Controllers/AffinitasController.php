<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AffinitasController extends Controller
{
    public function index()
    {
        return view('tools.affinitas.index');
    }
}
