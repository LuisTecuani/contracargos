<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AliadoUserCancellation extends Model
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
        $this->setConnection(config('database.aliado_connection'));
    }

    protected $table = 'user_cancellations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Get ids of users cancelled with an immovable reason.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getImmovableCancel()
    {
        return $this->select('user_id')
            ->whereIn('reason_id', ['1','2','3','4','5','6','7','8','9','17','26','40','42','43','44'])
            ->get()
            ->map(function ($item) {
                return $item->user_id;
            });
    }

    /*
|--------------------------------------------------------------------------
| Eloquent Query Scopes
|--------------------------------------------------------------------------
*/


}
