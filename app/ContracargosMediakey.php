<?php

namespace Contracargos;

use Illuminate\Database\Eloquent\Model;

class ContracargosMediakey extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_mediakey';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
