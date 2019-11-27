<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContracargosCellers extends Model
{
    /**
     * Specify the connection, since this implements multitenant solution
     * Called via constructor to faciliate testing
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('database.default'));
    }

    protected $guarded = [];

    protected $table = 'contracargos_cellers';

    protected $fillable = ['autorizacion',
        'tarjeta', 'user_id', 'email', 'fecha_rep'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
