<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediakeyFileMakingController extends Controller
{
    public function index()
    {
        return view("mediakey.file_making.index");
    }
}
