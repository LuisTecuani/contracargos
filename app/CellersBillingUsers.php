<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CellersBillingUsers extends Model
{
    protected $guarded = [];

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards() : HasMany
    {
        return $this->hasMany(CellersTdc::class, 'user_id', 'user_id')->latest();
    }
}
