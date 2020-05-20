<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CellersBlacklist extends Model
{
    protected $table = 'cellers_blacklist';

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
