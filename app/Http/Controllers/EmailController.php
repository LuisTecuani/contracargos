<?php

namespace App\Http\Controllers;

use App\Mail\ChargebackEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{


    public function chargeback(Request $request)
    {
        Mail::to("luisramos@thehiveteam.com")->send(new ChargebackEmail($request->data));
    }
}
