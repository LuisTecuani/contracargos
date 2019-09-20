<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContracargosCellers extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_cellers';

    protected $fillable = ['autorizacion',
        'tarjeta', 'user_id', 'email', 'fecha_rep'
    ];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
