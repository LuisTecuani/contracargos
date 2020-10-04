<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TdcVerificationController extends Controller
{
    public function index()
    {
        return view('tdc_verification.index');
    }

    public function show(Request $request)
    {
        $valid = [];

        $invalid = [];

        $tdcs = preg_split("[\n]", $request['tdcs']);

        foreach ($tdcs as $tdc)
        {
            if($this->validateLuhn($tdc)) {
                $valid[] = $tdc;
            } else {
                $invalid[] = $tdc;
            }
        }

        return view('tdc_verification.show', compact('valid', 'invalid'));
    }

    function luhn($number) {
        $odd = true;
        $sum = 0;

        foreach ( array_reverse(str_split($number)) as $num) {
            $sum += array_sum( str_split(($odd = !$odd) ? $num*2 : $num) );
        }

        return (($sum % 10 == 0) && ($sum != 0));
    }

    function validateLuhn(string $number): bool
    {
        $sum = 0;
        $revNumber = strrev($number);
        $len = strlen($number);

        for ($i = 0; $i < $len; $i++) {
            $sum += $i & 1 ? ($revNumber[$i] > 4 ? $revNumber[$i] * 2 - 9 : $revNumber[$i] * 2) : $revNumber[$i];
        }

        return $sum % 10 === 0;
    }
}
