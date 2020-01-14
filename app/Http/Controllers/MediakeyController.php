<?php


namespace App\Http\Controllers;

class MediakeyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("mediakey.index");
    }
}
