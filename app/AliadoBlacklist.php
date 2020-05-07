<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AliadoBlacklist extends Model
{
    protected $table = 'aliado_blacklist';

    protected $guarded = [];

    /*
|--------------------------------------------------------------------------
| Eloquent Query Scopes
|--------------------------------------------------------------------------
*/

    /**
     * Get all user_ids.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserIds($query)
    {
        return $query->select('user_id')->whereNotNull('user_id');
    }
}
