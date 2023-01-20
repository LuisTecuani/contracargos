<?php

namespace App\Http\Controllers;

use App\Helpers\AesEncryption;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

class AliadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $aes = new Encrypter('llaveDePrueba123', 'aes-128-cbc');

        $string = $aes->decrypt('eyJpdiI6IllYa29aNHNoQjJtVzBxb0lnREE1dkE9PSIsInZhbHVlIjoiODBRU3JTWTZia28vZEdTQ3FkenlnU0ZVUTZUWVVWbE9uVFlPMHpObVBXMD0iLCJtYWMiOiJmY2U2ZjE0NWZlMDVhYWRkNTViOTUyOTAyYzUwY2U3NmQwYzMzOGYxMWMyZmQ0YjJlYjMwOWM3OGU3YTc1MjFhIiwidGFnIjoiIn0=');



        return view("aliado.index", compact('string'));
    }

}
