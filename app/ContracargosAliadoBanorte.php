<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContracargosAliadoBanorte extends Model
{
    protected $guarded = [];

    protected $table = 'contracargos_aliado_banorte';

    protected $fillable = ['autorizacion', 'fecha_consumo',
        'tarjeta', 'user_id', 'email', 'fecha_rep'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

    /*
|--------------------------------------------------------------------------
| Eloquent Query Scopes
|--------------------------------------------------------------------------
*/

    /**
     * Get chargebacks created today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedToday($query)
    {
        return $query->select('email')
            ->whereDate('created_at', today());
    }

    /*
|--------------------------------------------------------------------------
| Eloquent Model Relationships
|--------------------------------------------------------------------------
*/

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reps() : HasMany
    {
        return $this->HasMany(RespuestasBanorteAliado::class, 'autorizacion', 'autorizacion');
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
