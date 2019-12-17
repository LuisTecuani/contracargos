<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliadoFileMakingController extends Controller
{
    public function index()
    {
        return view("aliado.file_making.index");
    }
}
