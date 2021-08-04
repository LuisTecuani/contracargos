<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrbanoFileMakingController extends Controller
{
    public function index()
    {
        return view("urbano.file_making.index");
    }
}
