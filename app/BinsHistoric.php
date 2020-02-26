<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BinsHistoric extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function bins()
    {
        return $this->belongsTo(Bin::class, 'bin', 'bin');
    }
}
