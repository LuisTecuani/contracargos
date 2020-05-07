<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AliadoCancelAccountAnswer extends Model
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

    protected $table = 'cancel_account_answers';

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
    public function getIds()
    {
        return $this->select('user_id')->get()->map(function ($item) {
            return $item->user_id;
        });
    }
}
