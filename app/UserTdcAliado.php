<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTdcAliado extends Model
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

    protected $table = 'user_tdc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];


}
