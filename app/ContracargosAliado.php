<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContracargosAliado extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_aliado';

    protected $fillable = ['autorizacion',
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
        return $this->HasMany(Repsaliado::class, 'autorizacion', 'autorizacion');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(AliadoUser::class);
    }
}
