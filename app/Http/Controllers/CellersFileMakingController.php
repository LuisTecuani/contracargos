<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CellersFileMakingController extends Controller
{
    public function index()
    {
        return view("cellers.file_making.index");
    }
}
