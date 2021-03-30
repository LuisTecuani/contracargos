<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrbanoPaycypsHistoric extends Model
{
    protected $guarded = [];

    public function getByFileName($fileName)
    {
        return $this->where('file_name', 'like', $fileName)->get();
    }
}
