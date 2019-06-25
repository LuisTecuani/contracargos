<?php

namespace Contracargos\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class importrepsController extends Controller
{

    public function check_file_existence($file, $table) {
        $source = Str::before($file->getClientOriginalName(), '.');

        $valid = DB::table("consultas.$table")
            ->where( 'source_file', 'like', $source)->get();
        return $valid;
    }

}
