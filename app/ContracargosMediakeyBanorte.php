<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContracargosMediakeyBanorte extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_mediakey_banorte';

    protected $fillable = ['autorizacion', 'fecha_consumo',
        'tarjeta', 'user_id', 'email', 'fecha_rep'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reps() : HasMany
    {
        return $this->HasMany(RespuestasBanorteMediakey::class, 'autorizacion', 'autorizacion');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(MediakeyUser::class);
    }
}
