<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContracargosAsmas extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_asmas';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
