<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrbanoUser extends Model
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
        $this->setConnection(config('database.urbano_connection'));
    }

    protected $table = 'users';
}
