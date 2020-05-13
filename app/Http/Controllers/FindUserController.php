<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FindUserController extends Controller
{
    public function index()
    {
        return view("find_user.index");
    }
}
