<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThxPaycypsBill extends Model
{
    protected $guarded = [];

    public function getByTdc($card)
    {
        return $this->where('tdc','like', $card)->get();
    }
}
